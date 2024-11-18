<?php

namespace App\Controller;

use App\Entity\Artist;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ArtistProfileController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    // Constructeur pour injecter EntityManager
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/artist/profile/{id}', name: 'artist_profile_show')]
    public function show($id): Response
    {
        // Charger l'artiste à partir de la base de données
        $artist = $this->entityManager->getRepository(Artist::class)->find($id);

        if (!$artist) {
            throw $this->createNotFoundException('L\'artiste spécifié n\'existe pas.');
        }

        return $this->render('artist_profils.html.twig', [
            'artist' => $artist
        ]);
    }

    #[Route('/artist/profile/{id}/edit', name: 'artist_profile_edit')]
    public function edit($id): Response
    {
        // Charger l'artiste à partir de la base de données
        $artist = $this->entityManager->getRepository(Artist::class)->find($id);

        if (!$artist) {
            throw $this->createNotFoundException('L\'artiste spécifié n\'existe pas.');
        }

        return $this->render('artist_profile_edit.html.twig', [
            'artist' => $artist
        ]);
    }
}
