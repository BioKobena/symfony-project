<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OffreController extends AbstractController
{
    #[Route('/offre', name: 'app_offre')]
    public function index(): Response
    {
        return $this->render('offre/index.html.twig', [
            'controller_name' => 'OffreController',
        ]);
    }

    #[Route('details', name: 'app_detail')]
    public function detail(): Response
    {
        return $this->render('offre/detail.html.twig', [
            'controller_name' => 'OffreController',
        ]);
    }

    #[Route('success', name: 'app_success')]
    public function success(): Response
    {
        return $this->render('offre/success.html.twig', [
            'controller_name' => 'OffreController',
        ]);
    }

}
