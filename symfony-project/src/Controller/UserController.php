<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if ($request->isMethod('POST')) {
            // Récupération des données POST
            $firstName = $request->request->get('firstName');
            $lastName = $request->request->get('lastName');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $location = $request->request->get('location');
            $bio = $request->request->get('bio');

            // Validation basique
            if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
                $this->addFlash('error', 'Veuillez remplir tous les champs obligatoires.');
                return $this->redirectToRoute('app_register');
            }

            // Création d'un nouvel utilisateur
            $user = new User();
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail($email);
            $user->setLocation($location);
            $user->setBio($bio);

            // Assurez-vous que le mot de passe n'est pas vide ou nul
            if (!empty($password)) {
                // Sauvegarde du mot de passe directement
                $user->setPassword($password);
            } else {
                $this->addFlash('error', 'Le mot de passe est requis.');
                return $this->redirectToRoute('app_register');
            }

            // Sauvegarde en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Message de succès
            $this->addFlash('success', 'Votre compte a été créé avec succès.');
        }

        return $this->render('user/index.html.twig');
    }

    #[Route('/users', name: 'app_user_list', methods: ['GET'])]
    public function list(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les utilisateurs
        $users = $entityManager->getRepository(User::class)->findAll();

        // Retourner les utilisateurs à la vue
        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }
}
