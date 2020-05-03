<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


use Symfony\Component\HttpKernel\KernelInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $lastUserName = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
        dump($request->getSession());
        return $this->render('security/login.html.twig', [
            'controller_name' => 'SecurityController',
            'lastUserName' => $lastUserName,
            'error' => $error,
            'route' => 'login'
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){}

    /**
     * @Route("/signup", name="signup")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param ObjectManager $manager
     * @return Response
     * @throws \Exception
     */
    public function signup(Request $request, UserPasswordEncoderInterface $encoder, ObjectManager $manager)
    {
        $user = new User();
        if (Count($request->request)) {
            $user->setEmail($request->request->get('email'))
                ->setFirstName($request->request->get('firstName'))
                ->setLastName($request->request->get('lastName'))
                ->setUserName($request->request->get('userName'))
                ->setPassword($encoder->encodePassword($user,$request->request->get('password')))
                ->setBirthday(new \DateTime($request->request->get('birthday')))
            ;
            $manager->persist($user);
            $manager->flush();

        }
        dump($request->request);
        return $this->render('security/signup.html.twig', [
            'controller_name' => 'SecurityController',
            'user'=>$user,
            'route' => 'signup'
        ]);
    }

    /**
     * @Route("/", name="home")
     * @param KernelInterface $kernel
     * @return Response
     */
    public function index(KernelInterface $kernel)
    {
        $content = "<p>";
        $projectDir = $kernel->getProjectDir();

        // Get the template content of this snippet
        $readme = file_get_contents($projectDir.'/readme.md');






        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
            'route' => 'home',
            'readme'=> $readme
        ]);
    }
}
