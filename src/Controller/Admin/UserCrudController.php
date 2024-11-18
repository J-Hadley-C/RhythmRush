<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            EmailField::new('email', 'Adresse Email'),
            ChoiceField::new('roles', 'Rôle')
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Artiste' => 'ROLE_ARTIST',
                    'Producteur' => 'ROLE_PRODUCTEUR',
                    'Beatmaker' => 'ROLE_BEATMAKER',
                    'Administrateur' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices()
                ->renderExpanded(false),
            TextField::new('nickname', 'Nom d’utilisateur'),
            TextField::new('phone', 'Téléphone')->hideOnIndex(),
            ImageField::new('photo', 'Photo de profil')
                ->setBasePath('/uploads/photos')
                ->setUploadDir('public/uploads/photos')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            BooleanField::new('isActive', 'Compte Actif'),
            DateTimeField::new('createdAt', 'Date de création')->onlyOnIndex(),
            DateTimeField::new('updatedAt', 'Dernière mise à jour')->onlyOnIndex(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs')
            ->setPageTitle(Crud::PAGE_INDEX, 'Gestion des Utilisateurs')
            ->setSearchFields(['email', 'nickname', 'phone'])
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('isActive')
            ->add('roles')
            ->add('createdAt');
    }
}
