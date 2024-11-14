<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtistController extends AbstractController
{
    #[Route('/artist', name: 'app_artist')]
    public function index(): Response
    {
        return $this->render('artist/index.html.twig', [
            'controller_name' => 'ArtistController',
        ]);
    }

    #[Route('/artist/overview', name: 'artist_overview')]
    public function overview(): Response
    {
        return $this->render('artist/overview.html.twig', [
            'title' => 'Présentation des Artistes',
            'description' => 'Découvrez comment RhythmRush aide les artistes à se connecter avec des producteurs et des beatmakers.',
        ]);
    }
}
