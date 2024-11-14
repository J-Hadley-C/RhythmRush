<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtistProfileController extends AbstractController
{
    #[Route('/artist/profile/{id}', name: 'artist_profile_show')]
    public function show($id): Response
    {
        // Code pour afficher le profil de l'artiste
    }

    #[Route('/artist/profile/{id}/edit', name: 'artist_profile_edit')]
    public function edit($id): Response
    {
        // Code pour modifier le profil de l'artiste
    }
}
