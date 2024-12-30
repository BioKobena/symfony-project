<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Developer;
// use App\Form\DeveloperType;
use Symfony\Component\HttpFoundation\Request;
use App\Service\AuthService;
use App\Entity\FicheDePoste;

class DeveloperController extends AbstractController
{
    #[Route('/developer', name: 'app_developer')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer toutes les fiches de poste
        $fichesDePoste = $entityManager->getRepository(FicheDePoste::class)->findAll();

        return $this->render('developer/index.html.twig', [
            'fiches_de_poste' => $fichesDePoste,
        ]);
    }

    #[Route('/developers', name: 'app_developers')]
    public function lastCreate(EntityManagerInterface $entityManager): Response
    {
        // Récupérer les 3 derniers développeurs créés
        $developers = $entityManager->getRepository(Developer::class)
            ->findBy([], ['createdAt' => 'DESC'], 3); // Trier par la date de création décroissante

        return $this->render('developer/index.html.twig', [
            'developers' => $developers,
        ]);
    }

    #[Route('/offre-info', name: 'app_infojob')]
    public function info_job(): Response
    {
        return $this->render('developer/offre.html.twig', [
            'controller_name' => 'DeveloperController',
        ]);
    }

    #[Route('/inscription-dev', name: 'app_inscription', methods: ['GET', 'POST'])]
    public function inscription_dev(Request $request, EntityManagerInterface $entityManager)
    {
        if ($request->isMethod('POST')) {
            // Récupération des données du formulaire
            $firstName = $request->request->get('firstName');
            $lastName = $request->request->get('lastName');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirmPassword');
            $location = $request->request->get('location');
            $languages = $request->request->get('languages');
            $experience = $request->request->get('experience');
            $salary = $request->request->get('salary');
            $bio = $request->request->get('bio');

            // Validation des champs
            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_inscription');
            }


            // Création de l'entité Developer
            $developer = new Developer();
            $developer->setNom($firstName)
                ->setPrenom($lastName)
                ->setEmail($email)
                ->setPassword($password) // Mot de passe non haché
                ->setLocalisation($location)
                ->setLanguages($languages)
                // ->setLanguages('languages', '[]') // Filtrage en cas de non tableau
                ->setExperience($experience)
                ->setSalaireMin($salary)
                ->setBio($bio);

            $developer->setPassword(password_hash($developer->getPassword(), PASSWORD_BCRYPT));

            $entityManager->persist($developer);
            $entityManager->flush();

            $this->addFlash('success', 'Votre inscription a été effectuée avec succès !');
            return $this->redirectToRoute('app_developer'); // Redirection après succès
        }
        return $this->render('developer/inscription.html.twig');
    }
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    #[Route('/auth-dev', name: 'app_developer_login', methods: ['GET', 'POST'])]
    public function loginDeveloper(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

            if (!$email) {
                $this->addFlash('error', 'Veuillez entrer votre adresse e-mail.');
                return $this->redirectToRoute('app_developer_login');
            }

            // Rechercher le développeur avec cet email
            $developer = $entityManager->getRepository(Developer::class)->findOneBy(['email' => $email]);

            if ($developer) {
                // // Connexion réussie
                // // Par exemple, stocker l'utilisateur en session
                // $session = $request->getSession();
                // $session->set('developer', $developer->getId());

                $this->addFlash('success', 'Connexion réussie !');
                return $this->redirectToRoute('app_developer'); // Redirigez vers une page pertinente
            } else {
                $this->addFlash('error', 'Adresse e-mail introuvable.');
                return $this->redirectToRoute('app_developer_login');
            }
        }

        return $this->render('developer/connexion.html.twig');
    }


}
