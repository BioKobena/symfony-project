<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Developer;
use Symfony\Component\HttpFoundation\Request;
use App\Service\AuthService;
use App\Entity\FicheDePoste;
use App\Entity\Favoris;
use App\Entity\User;
use App\Repository\DeveloperRepository;
use App\Repository\FicheDePosteRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Form\DeveloperType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;


// #[IsGranted('ROLE_DEV')]
class DeveloperController extends AbstractController
{
    #[Route('/developer', name: 'app_developer')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $developer = null;

        if ($user instanceof User) {
            $developer = $user->getDeveloper();
        }


        // Les postes récents
        $fichesDePoste = $entityManager->getRepository(FicheDePoste::class)
            ->findBy([], ['createdAt' => 'DESC'], 3);


        // dd($fichesDePoste);
        // Les postes populaires
        $offres = $entityManager->getRepository(FicheDePoste::class)
            ->findBy([], ['views' => 'DESC'], 3);

        // Passer toutes les données à la vue, y compris les fiches de poste
        return $this->render('developer/index.html.twig', [
            'fiches_de_poste' => $fichesDePoste,
            'offres' => $offres,
            'developer' => $developer,
        ]);

    }



    #[Route('/inscription-dev', name: 'app_inscription', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        SluggerInterface $slugger
    ): Response {
        $developer = new Developer();
        $form = $this->createForm(DeveloperType::class, $developer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Champs non mappés (qui n'appartiennent pas au Développeur de façon directe)
            $email = $form->get('email')->getData();
            $password = $form->get('password')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();

            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_developer_register');
            }

            // Création de l'utilisateur associé (User dans la base de données)
            $user = new User();
            $user->setEmail($email);
            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_DEV']);

            // Lier le Developer au User (Pour stocker ses identifiants et son rôle)
            $developer->setUser($user);

            // Gestion de l'upload de l'avatar
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
                    $developer->setAvatar($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'avatar.');
                    return $this->redirectToRoute('app_developer_login');
                }
            }

            // Enregistrement dans la base de données
            $entityManager->persist($user);
            $entityManager->persist($developer);
            $entityManager->flush();

            $this->addFlash('success', 'Inscription réussie !');
            return $this->redirectToRoute('app_developer_login');
        }

        return $this->render('developer/inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/offre-info', name: 'app_infojob')]
    public function offres(EntityManagerInterface $entityManager): Response
    {
        $offres = $entityManager->getRepository(FicheDePoste::class)->findAll();

        // Récupérer les 3 derniers développeurs créés
        return $this->render('developer/offre.html.twig', [
            'offres' => $offres,
        ]);
    }


    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    #[Route('/auth-dev', name: 'app_developer_login', methods: ['GET', 'POST'])]
    public function login(): Response
    {
        return $this->render('developer/connexion.html.twig');
    }

    #[Route('/search', name: 'app_search', methods: ['GET'])]
    public function search(Request $request, FicheDePosteRepository $ficheDePosteRepository)
    {
        $criteria = [
            'salairePropose' => $request->query->get('salary'),
            'localisation' => $request->query->get('location'),
            'niveauExperience' => $request->query->get('experience'),
        ];

        $fichesDePoste = $ficheDePosteRepository->searchFiches($criteria);

        return $this->render('offre/index.html.twig', [
            'fiches_de_poste' => $fichesDePoste,
        ]);
    }

    public function searchOffres(EntityManagerInterface $entityManager, string $searchTerm): array
    {
        $queryBuilder = $entityManager->getRepository(FicheDePoste::class)->createQueryBuilder('f');
        if (!empty($searchTerm)) {
            $queryBuilder->where('f.titre LIKE :search')
                ->orWhere('f.entreprise.nom LIKE :search')
                ->setParameter('search', '%' . $searchTerm . '%');
        }

        $offres = $queryBuilder->getQuery()->getResult();

        return $offres;
    }

    #[Route('/developer/add-fiche-favoris/{ficheId}', name: 'developer_add_fiche_favoris')]
    public function addFicheFavoris(int $ficheId, EntityManagerInterface $entityManager, DeveloperRepository $developerRepository, ): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier que l'utilisateur est bien connecté
        if (!$user) {
            return $this->redirectToRoute('app_developer_login');
        }


        // Récupérer le développeur correspondant à l'utilisateur connecté
        $developer = $developerRepository->findOneBy(['user' => $user]);

        if (!$developer) {
            return $this->redirectToRoute('app_developer_login');
        }

        $ficheDePoste = $entityManager->getRepository(FicheDePoste::class)->find($ficheId);

        if (!$ficheDePoste) {
            throw $this->createNotFoundException('Fiche de poste introuvable.');
        }

        // dd($ficheDePoste);

        $favoris = $entityManager->getRepository(Favoris::class)->findOneBy([
            'developer' => $developer,
            'ficheDePoste' => $ficheDePoste,
        ]);

        if (!$favoris) {
            $favoris = new Favoris();
            $favoris->setDeveloper($developer);
            $favoris->setFicheDePoste($ficheDePoste);

            $entityManager->persist($favoris);
            $entityManager->flush();

            $this->addFlash('success', 'Fiche de poste ajoutée à vos favoris avec succès !');
        } else {
            $this->addFlash('info', 'Cette fiche de poste est déjà dans vos favoris.');
        }

        return $this->redirectToRoute('app_favori');  // Redirection vers le tableau de bord du développeur
    }

    #[Route('/profil-dev', name: 'app_developer_profile')]
    // #[IsGranted('ROLE_DEV')] // S'assure que seul un utilisateur avec le rôle ROLE_DEV peut accéder
    public function profil(DeveloperRepository $developerRepository): Response
    {
        $user = $this->getUser();
        $developer = null;

        if ($user instanceof User) {
            $developer = $user->getDeveloper();
        }
        // $developer = $user->getd
        if (!$developer) {
            // Si aucun utilisateur n'est connecté
            throw $this->createAccessDeniedException('Vous devez être connecté pour voir cette page.');
        }

        return $this->render('developer/profile.html.twig', [
            'developer' => $developer,
        ]);
    }

    #[Route('/developer-update-{id}', name: 'app_update_profile')]
    public function updateProfile(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {


        $developer = $entityManager->getRepository(Developer::class)->find($id);

        if (!$developer) {
            throw $this->createNotFoundException('Développeur non trouvé.');
        }

        if ($request->isMethod('POST')) {
            $developer->setPrenom($request->request->get('firstName'));
            $developer->setNom($request->request->get('lastName'));
            $developer->setLocalisation($request->request->get('location'));
            $developer->setExperience($request->request->get('experience'));
            $developer->setSalaireMin($request->request->get('salary'));
            // $developer->setLanguages($request->request->get('languages'));
            $developer->setBio($request->request->get('bio'));

            $photo = $request->files->get('photo');
            if ($photo) {
                $uploadsDir = $this->getParameter('uploads_directory');
                $newFilename = uniqid() . '.' . $photo->guessExtension();
                $photo->move($uploadsDir, $newFilename);
                $developer->setAvatar($newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès.');

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('developer/update_profile.html.twig', [
            'developer' => $developer,
        ]);
    }
}
