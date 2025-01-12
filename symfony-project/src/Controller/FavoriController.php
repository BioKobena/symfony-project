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
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier que l'utilisateur est bien connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        // Récupérer le développeur correspondant à l'utilisateur connecté
        $developer = $developerRepository->findOneBy(['user' => $user]);

        // Vérifier que le développeur existe
        if (!$developer) {
            throw $this->createNotFoundException('Développeur non trouvé pour cet utilisateur.');
        }

        $favoris = $entityManager->getRepository(Favoris::class)
            ->findBy(['developer' => $developer]);


        // dd($favoris);
        // Associer les fiches de postes aux favoris
        $fichesDePoste = [];
        foreach ($favoris as $favori) {
            $fichesDePoste[] = $favori->getFicheDePoste();
        }
        // dd($fichesDePoste);



        // dd($fichesDePoste);
        return $this->render('favori/index.html.twig', [
            'fiches_de_poste' => $fichesDePoste,
        ]);
    }


    #[Route('/favori-company', name: 'app_favori_company')]
    public function companyFavoris(
        EntityManagerInterface $entityManager
    ): Response {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
    
        // Vérifier que l'utilisateur est bien connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }
    
        // Vérifier que l'utilisateur est associé à une entreprise
        $entreprise = $user->getCompany();
        if (!$entreprise) {
            return $this->redirectToRoute('app_company_login');

        }
    
        // Récupérer les favoris associés à l'entreprise
        $favoris = $entityManager->getRepository(Favoris::class)
            ->findBy(['company' => $entreprise]);
    
        // Récupérer les développeurs favoris
        $developers = [];
        foreach ($favoris as $favori) {
            $developers[] = $favori->getDeveloper();
        }
    
        return $this->render('favori/favoris_entreprise.html.twig', [
            'developers' => $developers,
            'company' => $entreprise,
        ]);
    }
    
    


    #[Route('/favori/remove/{id}', name: 'app_favori_remove')]
    public function removeFavori(
        int $id,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
    
        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour effectuer cette action.');
        }
    
        // Récupérer le favori par son ID
        $favori = $entityManager->getRepository(Favoris::class)->find($id);
    
        // Vérifier si le favori existe
        if (!$favori) {
            $this->addFlash('error', 'Favori introuvable.');
            return $this->redirectToRoute('app_favori');
        }
    
        // Vérifier si l'utilisateur a le droit de supprimer ce favori
        // Par exemple, si le favori est lié à une entreprise ou un utilisateur
        if ($favori->getCompany()->getUser() !== $user) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à supprimer ce favori.');
            return $this->redirectToRoute('app_favori');
        }
    
        // Supprimer le favori
        $entityManager->remove($favori);
        $entityManager->flush();
    
        $this->addFlash('success', 'Favori supprimé avec succès.');
    
        // Redirection vers la liste des favoris
        return $this->redirectToRoute('app_favori');
    }
    

/* 
    #[Route('/favori-company', name: 'favorite_company')]
    public function favoris_company(EntityManagerInterface $entityManager, DeveloperRepository $developerRepository): Response
    {
        $developer = $developerRepository->find(17); ou récupérer un utilisateur spécifique si nécessaire
        $favoris = $entityManager->getRepository(Favoris::class)
            ->findBy(['developer' => $developer]);

        // Associer les fiches de postes aux favoris
        $fichesDePoste = [];
        foreach ($favoris as $favori) {
            $fichesDePoste[] = $favori->getFicheDePoste();
        }

        return $this->render('favori/favoris_entreprise.html.twig', [
            'fiches_de_poste' => $fichesDePoste,
        ]);
    } */


}
