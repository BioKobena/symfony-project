<?php

namespace App\Controller;

use App\Entity\FicheDePoste;
use App\Entity\Company;
use App\Service\MatchingService;
use App\Service\NotificationService;
use App\Repository\DeveloperRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FicheDePosteController extends AbstractController
{
    #[Route('/entreprise-fiches-de-poste', name: 'create_fiche_de_poste')]
    public function createFicheDePoste(Request $request, EntityManagerInterface $entityManager, MatchingService $matchingService, NotificationService $notificationService, DeveloperRepository $developerRepository)
{
    // Récupération de l'entreprise de l'utilisateur connecté
    $company = $this->getUser()->getCompany();

    if (!$company) {
        return $this->json(['error' => 'Entreprise non associée à l\'utilisateur'], 404);
    }

    // Traitement POST
    if ($request->isMethod('POST')) {
        // Création d'une nouvelle fiche de poste
        $ficheDePoste = new FicheDePoste();
        $ficheDePoste->setCompany($company); // Associer directement l'entreprise de l'utilisateur connecté

        // Récupération des données de la requête
        $titre = $request->request->get('titre', '');
        $localisation = $request->request->get('localisation', '');
        $technologies = $request->request->get('technologies', ''); 
        $niveauExperience = $request->request->get('niveauExperience', 0);
        $salairePropose = $request->request->get('salairePropose', 0);
        $description = $request->request->get('description', '');

        // Affectation des données à la fiche de poste
        $ficheDePoste->setTitre($titre)
            ->setLocalisation($localisation)
            ->setTechnologies($technologies) 
            ->setNiveauExperience($niveauExperience)
            ->setSalairePropose($salairePropose)
            ->setDescription($description);

        // Ajout de la date de création
        $ficheDePoste->setCreatedAt(new \DateTime());

        // Persistance de la fiche de poste
        $entityManager->persist($ficheDePoste);
        $entityManager->flush();

        // Récupération des développeurs pour envoyer les notifications
        $developers = $developerRepository->findAll();
        foreach ($developers as $developer) {
            $score = $matchingService->calculateMatchScore($developer, $ficheDePoste);

            // Si le score est suffisant, créer une notification
            if ($score >= 50) {
                $notificationService->notifyDeveloper($developer, $ficheDePoste);
            }
        }

        // Retour ou redirection après succès
        return $this->redirectToRoute('app_company');
    }

    // Récupération des fiches de poste associées à cette entreprise
    $fichesDePoste = $entityManager->getRepository(FicheDePoste::class)
        ->findBy(['company' => $company]);

    // Affichage du formulaire
    return $this->render('fiche_de_poste/index.html.twig', [
        'company' => $company,
        'fichesDePoste' => $fichesDePoste,
    ]);
}
}
