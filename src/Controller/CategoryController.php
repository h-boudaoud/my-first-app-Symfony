<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{
    private $categoryRepository;
    private $categories;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categories = $this->categoryRepository->findAll();
    }

    /**
     * @Route("/articles/category-{id}", name="articles_by_category")
     * @Route("/articles", name="articles")
     * @param int $id
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function articlesList(ArticleRepository $articleRepository, $id = null)
    {
        $categories = $this->categoryRepository->findAll();
        $articles = null;
        if (!$id) {
            $category = null;
        } else {
            $category = $this->categoryRepository->find($id);
        }

        if (!$category) {
            $category = new Category();
            $category->setName((!$id) ? 'All category' : '-- Error : Category not find');
            $articles = $articleRepository->findAll();
        } else {
            $articles = $category->getArticles();
        }

        // dump($request->getUri(), $request->request, $categories, $category);
        return $this->render('category/articlesList.html.twig', [
            'controller_name' => 'CategoryController',
            'articles' => $articles,
            'categories' => $categories,
            'category' => $category,
            'id' => !$id ? 'All' : $id
        ]);
    }

    /**
     * @Route("/categories", name="categories")
     * @Route("/categories/json", name="categories_json")
     * @param Request $request
     * @return Response
     */
    public function categoriesList(Request $request)
    {
        $categories = $this->categoryRepository->findAll();
        if (strpos($request->getUri(), 'json')) {
            $data = null;
            foreach ($categories as $category) {
                $data[] = $category->jsonSerialize();
            }

            // dump($data ) ;

//            $data = ['data' => json_encode($categories)];
//            foreach ($categories as $item) {
//                $obj= ['id'=>$item->getId(), 'id'=>$item->getName(), 'id'=>$item->getArticles()];
//                $data[] = $obj;
//            }
            return $this->json(["code"=>200, "data"=>$data, "message"=>"success"], 200);
        }

        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/category/new", name="category_new")
     * @Route("/category/{id}/edit", name="category_edit")
     * @param Category|null $category
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */


    public function formCategory(Request $request, ObjectManager $manager, Category $category = null)
    {


        if (!$category) {
            $category = new Category();
        }

        if (Count($request->request)) {
            $category->setName($request->request->get('name'));
            $manager->persist($category);
            $manager->flush();
            return $this->redirect($request->headers->get('referer'), 302);
        }

        $categories = $this->categoryRepository->findAll();

        // dump($request->getUri(), $request->request, $categories, $category);
        return $this->render('category/form.html.twig', [
            'controller_name' => 'CategoryController',
            'category' => $category,
            'categories' => $categories,
            'title' => !$category->getName() ? 'new category' : 'edit ' . $category->getId()
        ]);
    }
}
