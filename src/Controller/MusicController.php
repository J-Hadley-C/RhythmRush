<?php

namespace App\Controller;

use App\Entity\Music;
use App\Form\MusicType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MusicController extends AbstractController
{
    #[Route('/music/create', name: 'music_create')]
    #[IsGranted('create', subject: 'music')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $music = new Music();
        $form = $this->createForm(MusicType::class, $music);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $music->setCreator($this->getUser());
            $entityManager->persist($music);
            $entityManager->flush();

            return $this->redirectToRoute('music_show', ['id' => $music->getId()]);
        }

        return $this->render('music/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/music/{id}', name: 'music_show')]
    public function show(Music $music): Response
    {
        return $this->render('music/show.html.twig', [
            'music' => $music,
        ]);
    }

    #[Route('/music/{id}/edit', name: 'music_edit')]
    #[IsGranted('edit', subject: 'music')]
    public function edit(Music $music, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MusicType::class, $music);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('music_show', ['id' => $music->getId()]);
        }

        return $this->render('music/edit.html.twig', [
            'form' => $form->createView(),
            'music' => $music,
        ]);
    }

    #[Route('/music/{id}/delete', name: 'music_delete', methods: ['POST'])]
    #[IsGranted('delete', subject: 'music')]
    public function delete(Music $music, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$music->getId(), $request->request->get('_token'))) {
            $entityManager->remove($music);
            $entityManager->flush();
        }

        return $this->redirectToRoute('music_list');
    }
}
