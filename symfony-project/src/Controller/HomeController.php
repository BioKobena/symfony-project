<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\FicheDePoste;
use App\Entity\Developer;
use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Les postes populaires
        $offres = $entityManager->getRepository(FicheDePoste::class)
            ->count([]);

        // Compter le nombre de développeurs
        $nombreDevs = $entityManager->getRepository(Developer::class)->count([]);

        // Compter le nombre d'entreprises
        $nombreEntreprises = $entityManager->getRepository(Company::class)->count([]);


        return $this->render('home/index.html.twig', [
            'offres' => $offres,
            'nombreDevs' => $nombreDevs,
            'nombreEntreprises' => $nombreEntreprises,
        ]);
    }
}
