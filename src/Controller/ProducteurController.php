<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProducteurController extends AbstractController
{
    #[Route('/producteur', name: 'app_producteur')]
    public function index(): Response
    {
        return $this->render('producteur/index.html.twig', [
            'controller_name' => 'ProducteurController',
        ]);
    }

    #[Route('/producteur/overview', name: 'producer_overview')]
    public function overview(): Response
    {
        return $this->render('producteur/overview.html.twig', [
            'title' => 'Présentation des Producteurs',
        ]);
    }
}
