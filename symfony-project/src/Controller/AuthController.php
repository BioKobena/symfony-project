<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{

    // Développeurs
    #[Route('/auth', name: 'app_auth')]
    public function index(): Response
    {
        return $this->render('auth/index.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }

    #[Route('/auth/signin', name: 'app_signin')]
    public function signin(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }


    // Connexion entreprise
    #[Route('/auth-company', name: 'auth-company')]
    public function login_company(): Response
    {
        return $this->render('auth/auth-company.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }

    #[Route('/signin-company', name: 'signin-company')]
    public function signin_company(): Response
    {
        return $this->render('auth/signin-company.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }
}
