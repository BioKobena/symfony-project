<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\FicheDePoste;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


class OffreController extends AbstractController
{
    #[Route('/offre', name: 'app_offre')]
    public function index(): Response
    {
        return $this->render('offre/index.html.twig', [
            'controller_name' => 'OffreController',
        ]);
    }

    #[Route('/{id}', name: 'app_detail')]
    public function detail($id, EntityManagerInterface $entityManager): Response
    {
        $fiche = $entityManager->getRepository(FicheDePoste::class)->find($id);

        if (!$fiche) {
            throw $this->createNotFoundException('Offre introuvable');
        }

        $fiche->incrementViews();

        $entityManager->flush();

        return $this->render('offre/detail.html.twig', [
            'fiche' => $fiche,
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
