<?php

namespace App\Controller;

use App\Service\ChampionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ChampionService $championService): Response
    {
        $img_url = $championService->getChampionImageURL();

        return $this->render('home/index.html.twig', [
            'img_url' => $img_url,
        ]);
    }
}
