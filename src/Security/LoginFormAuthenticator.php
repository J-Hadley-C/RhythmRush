<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    private UrlGeneratorInterface $urlGenerator;
    private UserPasswordHasherInterface $passwordHasher;
    private TokenStorageInterface $tokenStorage;
    private AuthorizationCheckerInterface $authorizationChecker;

    // Constructeur avec les dépendances injectées
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        UserPasswordHasherInterface $passwordHasher,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->passwordHasher = $passwordHasher;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    // Détermine si l'authentificateur doit être utilisé sur cette requête
    public function supports(Request $request): bool
    {
        // Supporte uniquement la route "app_login" avec la méthode POST
        return $request->attributes->get('_route') === 'app_login' && $request->isMethod('POST');
    }

    // Crée un Passport (passeport d'authentification) à partir de la requête
    public function authenticate(Request $request): Passport
    {
        // Récupération des champs "email" et "password" depuis la requête
        $email = $request->request->get('_username');
        $password = $request->request->get('_password');

        if (!$email || !$password) {
            // Lancer une exception si les champs requis ne sont pas fournis
            throw new CustomUserMessageAuthenticationException('Veuillez fournir un email et un mot de passe.');
        }

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password)
        );
    }

    // Vérifie la validité des informations d'identification
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        // S'assurer que l'utilisateur est bien une instance de l'entité User
        if (!$user instanceof User) {
            throw new CustomUserMessageAuthenticationException('Utilisateur non valide.');
        }

        // Vérifie si le compte utilisateur est actif (par exemple, email confirmé)
        if (!$user->isActive()) {
            throw new CustomUserMessageAuthenticationException(
                'Veuillez confirmer votre compte par email avant de vous connecter.'
            );
        }

        // Utilise UserPasswordHasher pour vérifier le mot de passe
        return $this->passwordHasher->isPasswordValid($user, $credentials->getPassword());
    }

    // Appelé lorsqu'une authentification réussit
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Redirige en fonction du rôle de l'utilisateur
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($this->urlGenerator->generate('admin_dashboard'));
        }

        // Redirection vers la page d'accueil par défaut
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    // Appelé lorsqu'une authentification échoue
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        // Ajoute un message d'erreur à la session pour l'utilisateur
        $request->getSession()->getFlashBag()->add("error", "Échec de l'authentification. Veuillez vérifier vos identifiants.");

        // Redirige vers la page de connexion
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }

    // Retourne l'URL de la page de connexion
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('app_login');
    }
}
