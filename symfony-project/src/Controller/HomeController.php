<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\FicheDePoste;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Les postes populaires
        $offres = $entityManager->getRepository(FicheDePoste::class)
            ->findBy([], ['views' => 'DESC'], 3);


        return $this->render('home/index.html.twig', [
            'offres' => $offres,
        ]);

    }
}
