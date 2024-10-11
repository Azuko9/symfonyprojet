<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Récupérer les rôles sélectionnés
            $roles = $form->get('roles')->getData();
            // Vérifie si l'utilisateur tente de créer un profil administrateur


            // Attribuer les rôles à l'utilisateur
            $user->setRoles($roles);

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_utilisateur');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function promoteToAdmin(int $userId): Response
    {
        // Récupérer l'utilisateur par son ID
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return new Response('Utilisateur non trouvé', 404);
        }

        // Ajouter ROLE_ADMIN au tableau des rôles existants
        $roles = $user->getRoles();
        $roles[] = 'ROLE_ADMIN';
        $user->setRoles(array_unique($roles)); // Utiliser array_unique pour éviter les doublons

        // Sauvegarder les changements dans la base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new Response('Utilisateur promu à ROLE_ADMIN');
    }
}
