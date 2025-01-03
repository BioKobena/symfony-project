<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Favoris;
use App\Repository\DeveloperRepository;
use App\Entity\Company;
use App\Entity\Developer;
use App\Repository\CompanyRepository;

class FavoriController extends AbstractController
{
    #[Route('/favori', name: 'app_favori')]
    public function index(EntityManagerInterface $entityManager, DeveloperRepository $developerRepository): Response
    {
        $developer = $developerRepository->find(17); // ou récupérer un utilisateur spécifique si nécessaire
        $favoris = $entityManager->getRepository(Favoris::class)
            ->findBy(['developer' => $developer]);

        // Associer les fiches de postes aux favoris
        $fichesDePoste = [];
        foreach ($favoris as $favori) {
            $fichesDePoste[] = $favori->getFicheDePoste();
        }

        return $this->render('favori/index.html.twig', [
            'fiches_de_poste' => $fichesDePoste,
        ]);
    }


    #[Route('/favori-company', name: 'app_favori_company')]
    public function company_favoris(EntityManagerInterface $entityManager, CompanyRepository $companyRepository): Response
    {
        $entreprise = $companyRepository->find(17); // ou récupérer un utilisateur spécifique si nécessaire
        $favoris = $entityManager->getRepository(Favoris::class)
            ->findBy(['company' => $entreprise]);

        // Associer les fiches de postes aux favoris
        $fichesDePoste = [];
        foreach ($favoris as $favori) {
            $fichesDePoste[] = $favori->getDeveloper();
        }

        return $this->render('favori/favoris_entreprise.html.twig', [
            'fiches_de_poste' => $fichesDePoste,
        ]);
    }

    #[Route('/favori/remove/{id}', name: 'app_favori_remove')]
    public function removeFavori(int $id, EntityManagerInterface $entityManager): Response
    {
        $favori = $entityManager->getRepository(Favoris::class)->find($id);

        if ($favori) {
            $entityManager->remove($favori);
            $entityManager->flush();
            $this->addFlash('success', 'Favori supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Favori introuvable.');
        }

        return $this->redirectToRoute('app_favori');
    }


    // #[Route('/favori-company', name: 'favorite_company')]
    // public function favoris_company(EntityManagerInterface $entityManager, DeveloperRepository $developerRepository): Response
    // {
    //     $developer = $developerRepository->find(17); // ou récupérer un utilisateur spécifique si nécessaire
    //     $favoris = $entityManager->getRepository(Favoris::class)
    //         ->findBy(['developer' => $developer]);

    //     // Associer les fiches de postes aux favoris
    //     $fichesDePoste = [];
    //     foreach ($favoris as $favori) {
    //         $fichesDePoste[] = $favori->getFicheDePoste();
    //     }

    //     return $this->render('favori/favoris_entreprise.html.twig', [
    //         'fiches_de_poste' => $fichesDePoste,
    //     ]);
    // }

   
}
