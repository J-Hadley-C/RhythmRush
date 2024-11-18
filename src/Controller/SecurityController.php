<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, redirige-le vers la page appropriée en fonction de son rôle
        if ($this->getUser()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                // Redirige les administrateurs vers le tableau de bord admin
                return $this->redirectToRoute('admin_dashboard');
            }

            // Redirige les autres utilisateurs vers la page d'accueil
            return $this->redirectToRoute('app_home');
        }

        // Récupère l'erreur d'authentification s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupère le dernier nom d'utilisateur saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        // Rend la vue de la page de connexion avec les éventuelles erreurs et le dernier nom d'utilisateur saisi
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Symfony gère automatiquement la déconnexion.
        // Cette méthode ne sera jamais exécutée mais doit être définie pour la route de déconnexion.
        throw new \LogicException('Cette méthode peut être vide. La déconnexion est gérée par le système de sécurité de Symfony.');
    }
}
