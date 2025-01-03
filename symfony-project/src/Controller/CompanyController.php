<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Company;
use App\Entity\Favoris;
use App\Repository\DeveloperRepository;
use App\Entity\Developer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\AsciiSlugger;


class CompanyController extends AbstractController
{
    #[Route('/company', name: 'app_company')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupération des 3 derniers développeurs
        $developers = $entityManager->getRepository(Developer::class)
            ->findBy([], ['id' => 'DESC'], 3); // Les 3 derniers développeurs

        // Récupérer les 3 développeurs les plus consultés
        $mostConsulted = $entityManager->getRepository(Developer::class)
            ->findBy([], ['views' => 'DESC'], 3);

        return $this->render('company/index.html.twig', [
            'developers' => $developers,
            'mostConsulted' => $mostConsulted,
        ]);
    }


    #[Route('/company-login', name: 'app_company_login', methods: ['GET', 'POST'])]
    public function login_company(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

            if (!$email) {
                $this->addFlash('error', 'Veuillez entrer votre adresse e-mail.');
                return $this->redirectToRoute('app_company_login');
            }

            // Rechercher le développeur avec cet email
            $company = $entityManager->getRepository(Company::class)->findOneBy(['email' => $email]);

            if ($company) {
                // // Connexion réussie
                // // Par exemple, stocker l'utilisateur en session
                // $session = $request->getSession();
                // $session->set('developer', $developer->getId());

                $this->addFlash('success', 'Connexion réussie !');
                return $this->redirectToRoute('app_company'); // Redirigez vers une page pertinente
            } else {
                $this->addFlash('error', 'Adresse e-mail introuvable.');
                return $this->redirectToRoute('app_company_login');
            }
        }

        return $this->render('company/connexion-entreprise.html.twig');
    }
    #[Route('/inscription-company', name: 'inscription-company')]
    public function inscription_company(Request $request, EntityManagerInterface $entityManager)
    {
        if ($request->isMethod('POST')) {
            // Récupération des données du formulaire
            $nom = $request->request->get('nom');
            $localisation = $request->request->get('localisation');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirmPassword');
            $description = $request->request->get('description');
            $tailleEntreprise = $request->request->get('taille_entreprise');
            $secteur = $request->request->get('secteur');
            $typeEntreprise = $request->request->get('type_entreprise');


            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_inscription');
            }

            // Validation des champs
            // On pourrait ajouter des validations supplémentaires comme vérifier si le mot de passe est confirmé

            // Création de l'entité Company
            $company = new Company();
            $company->setNom($nom)
                ->setLocalisation($localisation)
                ->setEmail($email)
                ->setPassword($password) // Mot de passe non haché
                ->setDescription($description)
                ->setTailleEntreprise($tailleEntreprise)
                ->setSecteur($secteur)
                ->setTypeEntreprise($typeEntreprise);

            // Encoder le mot de passe
            $company->setPassword(password_hash($company->getPassword(), PASSWORD_BCRYPT));

            $entityManager->persist($company);
            $entityManager->flush();

            $this->addFlash('success', 'Votre entreprise a été inscrite avec succès !');
            return $this->redirectToRoute('app_company'); // Redirection après succès
        }

        return $this->render('company/inscription-company.html.twig');
    }

    #[Route('/{id}-developper-adopte-un-dev', name: 'developer_detail')]
    public function showDeveloper(int $id, EntityManagerInterface $entityManager): Response
    {
        $developer = $entityManager->getRepository(Developer::class)->find($id);

        if (!$developer) {
            throw $this->createNotFoundException('Développeur introuvable.');
        }

        $developer->incrementViews();
        $entityManager->flush();

        return $this->render('company/dev-info.html.twig', [
            'developer' => $developer,
        ]);
    }

    #[Route('/favorites/add/{developperId}', name: 'company_add_fiche_favoris')]
    public function add(int $developperId, Developer $developer, EntityManagerInterface $entityManager): Response
    {
        $company = $entityManager->getRepository(Company::class)->find(1); // Assurez-vous que l'utilisateur est une entreprise

        $developer = $entityManager->getRepository(Developer::class)->find($developperId);

        if (!$developer) {
            throw $this->createNotFoundException('Fiche de poste introuvable.');
        }


        // Vérifiez si le favori existe déjà
        $existingFavorite = $entityManager->getRepository(Favoris::class)->findOneBy([
            'company' => $company,
            'developer' => $developer,
        ]);


        if (!$existingFavorite) {
            $favorite = new Favoris();
            $favorite->setCompany($company);
            $favorite->setDeveloper($developer);

            $entityManager->persist($favorite);
            $entityManager->flush();

            $this->addFlash('success', 'Développeur ajouté aux favoris.');
        } else {
            $this->addFlash('info', 'Ce développeur est déjà dans vos favoris.');
        }

        return $this->redirectToRoute('app_company');
    }

}
