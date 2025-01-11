<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Company;
use App\Form\CompanyType;
use App\Entity\Favoris;
use App\Repository\DeveloperRepository;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Developer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;


use Symfony\Component\HttpFoundation\JsonResponse;

class CompanyController extends AbstractController
{
    #[Route('/company', name: 'app_company')]
    public function index(EntityManagerInterface $entityManager): Response
    {



        $user = $this->getUser();
        $company = null;
        $userId = null;
        // dd($user);

        if ($user instanceof User) {
            $userId = $user->getId();
            $company = $user->getCompany();
        }

        // Récupération des 3 derniers développeurs
        $developers = $entityManager->getRepository(Developer::class)
            ->findBy([], ['id' => 'DESC'], 3); // Les 3 derniers développeurs

        // Récupérer les 3 développeurs les plus consultés
        $mostConsulted = $entityManager->getRepository(Developer::class)
            ->findBy([], ['views' => 'DESC'], 3);

        return $this->render('company/index.html.twig', [
            'developers' => $developers,
            'mostConsulted' => $mostConsulted,
            'company' => $company
        ]);
    }


    #[Route('/company-login', name: 'app_company_login', methods: ['GET', 'POST'])]
    public function login(): Response
    {
        return $this->render('company/connexion-entreprise.html.twig');
    }

    #[Route('/inscription-company', name: 'inscription-company')]
    public function inscriptionCompany(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        SluggerInterface $slugger

    ) {
        // Créer une nouvelle instance d'entité Company
        $company = new Company();


        // Crée un nouvel utilisateur pour l'entreprise
        $user = new User();

        // Créer le formulaire de l'entreprise
        $form = $this->createForm(CompanyType::class, $company);

        // Gérer l'envoi du formulaire
        $form->handleRequest($request);

        $avatarFile = $form->get('avatar')->getData();
        if ($avatarFile) {
            $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $avatarFile->guessExtension();

            try {
                $avatarFile->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );
                $company->setAvatar($newFilename);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'upload de l\'avatar.');
                return $this->redirectToRoute('app_developer_login');
            }
        }
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données du formulaire
            // $data = $form->getData();

            // // Récupérer le mot de passe et le confirmer
            // $password = $data['password'];
            // $confirmPassword = $data['confirmPassword'];
            $email = $form->get('email')->getData();
            $password = $form->get('password')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();

            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_inscription_company');
            }

            // Hashage du mot de passe
            $user->setRoles(['ROLE_COMPANY']);
            $user->setEmail($email)
                ->setPassword($passwordHasher->hashPassword($user, $password));

            // Sauvegarde de l'utilisateur
            $entityManager->persist($user);

            // Sauvegarde de l'entreprise avec l'utilisateur associé
            $company->setUser($user);
            $entityManager->persist($company);
            $entityManager->flush();

            // Message de succès
            $this->addFlash('success', 'Votre entreprise a été inscrite avec succès !');

            // Rediriger après inscription
            return $this->redirectToRoute('app_company_login');
        }

        // Si le formulaire n'est pas soumis ou n'est pas valide, afficher le formulaire
        return $this->render('company/inscription-company.html.twig', [
            'form' => $form->createView(),
        ]);
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
    public function add(
        int $developperId, 
        EntityManagerInterface $entityManager
    ): Response {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
    
        // Vérifiez que l'utilisateur est bien connecté et qu'il est associé à une entreprise
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour effectuer cette action.');
        }
    
        $company = $entityManager->getRepository(Company::class)->findOneBy(['user' => $user]);
    
        if (!$company) {
            throw $this->createNotFoundException('Aucune entreprise associée à cet utilisateur.');
        }
    
        // Récupérer le développeur par ID
        $developer = $entityManager->getRepository(Developer::class)->find($developperId);
    
        if (!$developer) {
            throw $this->createNotFoundException('Développeur introuvable.');
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
    

    #[Route("/search-developers", name: "search_developers", methods: ['GET'])]
    public function searchDevelopers(Request $request, DeveloperRepository $developerRepository): Response
    {
        $criteria = $request->query->all();

        $developers = $developerRepository->findByCriteria($criteria);

        return $this->render('profil/index.html.twig', [
            'developers' => $developers,
        ]);
    }
}
