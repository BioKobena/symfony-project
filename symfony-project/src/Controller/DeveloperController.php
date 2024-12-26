<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeveloperController extends AbstractController
{
    #[Route('/developer', name: 'app_developer')]
    public function index(): Response
    {
        return $this->render('developer/index.html.twig', [
            'controller_name' => 'DeveloperController',
        ]);
    }

    #[Route('/offre-info', name: 'app_infojob')]
    public function info_job(): Response
    {
        return $this->render('developer/offre.html.twig', [
            'controller_name' => 'DeveloperController',
        ]);
    }
}
