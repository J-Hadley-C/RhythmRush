<?php

namespace App\Controller\Admin;

use App\Entity\Beatmaker;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class BeatmakerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Beatmaker::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name', 'Nom'),
            TextField::new('nickname', 'Pseudo')->hideOnIndex(),
            TextField::new('bio', 'Biographie')->hideOnIndex(),
            TextField::new('city', 'Ville')->hideOnIndex(),
            ImageField::new('photo')
                ->setBasePath('/uploads/photos')
                ->setUploadDir('public/uploads/photos')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            DateTimeField::new('createdAt', 'Date de création')->onlyOnIndex(),
            AssociationField::new('user', 'Utilisateur associé')->hideOnForm(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Beatmaker')
            ->setEntityLabelInPlural('Beatmakers')
            ->setPageTitle(Crud::PAGE_INDEX, 'Gestion des Beatmakers')
            ->setSearchFields(['name', 'nickname', 'bio', 'city'])
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('createdAt')
            ->add('name')
            ->add('nickname')
            ->add('city');
    }
}
