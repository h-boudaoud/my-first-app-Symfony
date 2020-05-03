<?php

namespace App\Controller;
use App\Entity\Article;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{


    /**
     * @Route("/article/{id}", name="article_byId")
     * @param null $id
     * @param ArticleRepository $articleRepository
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index($id = null, ArticleRepository $articleRepository, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        $article = null;
        try {
            $article = $articleRepository->find($id);
            $category = $article->getCategory();

        }catch (\Exception $e) {
            $article = new Article();
            $category = new Category();
            $category->setName('-- Error'.$e->getMessage());
        }

        // dump($request->getUri(), $request->request, $categories, $category);
        return $this->render('article/index.html.twig', [
            'controller_name' => 'CategoryController',
            'article' => $article,
            'categories' => $categories,
            'category' => $category,
            'id' => !$id ? 'All' : $id
        ]);
    }
}
