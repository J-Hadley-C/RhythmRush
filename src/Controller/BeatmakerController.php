<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BeatmakerController extends AbstractController
{
    #[Route('/beatmaker', name: 'app_beatmaker')]
    public function index(): Response
    {
        return $this->render('beatmaker/index.html.twig', [
            'controller_name' => 'BeatmakerController',
        ]);
    }

    #[Route('/beatmaker/overview', name: 'beatmaker_overview')]
    public function overview(): Response
    {
        return $this->render('beatmaker/overview.html.twig', [
            'title' => 'Présentation des Beatmakers',
        ]);
    }
}
