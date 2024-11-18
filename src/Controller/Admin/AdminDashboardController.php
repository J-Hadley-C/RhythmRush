<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Artist;
use App\Entity\Beatmaker;
use App\Entity\Producteur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminDashboardController extends AbstractDashboardController
{
    private AdminUrlGenerator $adminUrlGenerator;

    // Injection du service via le constructeur
    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function index(): Response
    {
        // Vérifie que seul un utilisateur avec le rôle ROLE_ADMIN peut accéder au tableau de bord
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Vous n\'avez pas les droits nécessaires pour accéder à cette page.');

        // Supprime le dump utilisé pour le débogage
        // dump($this->getUser()->getRoles()); // Enlever ce dump pour éviter de montrer des informations sensibles

        // Option 1: Rediriger vers une page de gestion (comme UserCrudController)
        // Si vous préférez rediriger immédiatement vers la page de gestion des utilisateurs :
        $url = $this->adminUrlGenerator
            ->setController(UserCrudController::class)
            ->generateUrl();
        $url = $this->adminUrlGenerator
            ->setController(UserCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    

        // Option 2: Rendre directement le tableau de bord avec un template
        // Si vous préférez afficher le tableau de bord directement, sans redirection :
        return parent::index();
    }

    // Configuration du tableau de bord, titre, etc.
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Hsk Administration');
    }

    // Configuration des éléments du menu du tableau de bord
    public function configureMenuItems(): iterable
    {
        // Lien vers le tableau de bord principal
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        // Lien vers la gestion des utilisateurs
        yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class)
            ->setController(UserCrudController::class);

        // Lien vers la gestion des artistes
        yield MenuItem::linkToCrud('Artists', 'fa fa-music', Artist::class)
            ->setController(ArtistCrudController::class);

        // Lien vers la gestion des producteurs
        yield MenuItem::linkToCrud('Producteurs', 'fa fa-industry', Producteur::class)
            ->setController(ProducteurCrudController::class);

        // Lien vers la gestion des beatmakers
        yield MenuItem::linkToCrud('Beatmakers', 'fa fa-drum', Beatmaker::class)
            ->setController(BeatmakerCrudController::class);
    }
}
