<?php

namespace App\Controller;

use App\Entity\FicheDePoste;
use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FicheDePosteController extends AbstractController
{
    // Récupération de l'entreprise par son ID pour effectuer une route spécifique à elle ([1..n] à remplacer par {id})
    #[Route('/entreprise/3/fiches-de-poste/create', name: 'create_fiche_de_poste')]
    public function createFicheDePoste(Request $request, EntityManagerInterface $entityManager)
    {
        // Récupération de l'entreprise par son ID
        $company = $entityManager->getRepository(Company::class)->find(3);

        if (!$company) {
            return $this->json(['error' => 'Entreprise non trouvée'], 404);
        }

        // Traitement POST
        if ($request->isMethod('POST')) {
            // Création d'une nouvelle fiche de poste
            $ficheDePoste = new FicheDePoste();
            $ficheDePoste->setEntreprise($company); // Associer directement l'entreprise

            // Récupération des données de la requête
            $titre = $request->request->get('titre', '');
            $localisation = $request->request->get('localisation', '');
            $technologies = $request->request->get('technologies', ''); // Pas de décodage JSON
            $niveauExperience = $request->request->get('niveauExperience', 0);
            $salairePropose = $request->request->get('salairePropose', 0);
            $description = $request->request->get('description', '');

            // Affectation des données à la fiche de poste
            $ficheDePoste->setTitre($titre)
                ->setLocalisation($localisation)
                ->setTechnologies($technologies) // Pas de JSON
                ->setNiveauExperience($niveauExperience)
                ->setSalairePropose($salairePropose)
                ->setDescription($description);

            // Persistance de la fiche de poste
            $entityManager->persist($ficheDePoste);
            $entityManager->flush();

            // Retour ou redirection après succès
            return $this->redirectToRoute('app_company');
        }

        // Affichage du formulaire
        return $this->render('fiche_de_poste/index.html.twig', [
            'company' => $company,
        ]);
    }

    
}
