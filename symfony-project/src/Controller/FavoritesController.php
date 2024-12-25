<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FavoritesController extends AbstractController
{
    #[Route('/favorites', name: 'app_favorites')]
    public function index(): Response
    {
        return $this->render('favorites/index.html.twig', [
            'controller_name' => 'FavoritesController',
        ]);
    }
}
