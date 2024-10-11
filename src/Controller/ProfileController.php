<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Security $security): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $security->getUser();

        // Renvoyer une erreur 404 si l'utilisateur n'est pas trouvé
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        // Passer l'utilisateur à la vue
        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }
}
