<?php

namespace App\Controller;

use App\Service\ChampionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    // Route pour effacer le cookie remember me
    #[Route('/clearRememberme', name: 'app_rememberme', methods: ['POST'])]
    public function clearRememberme(Request $request)
    {
        $response = new Response();
        if ($request->cookies->has('REMEMBERME')) {
            $response->headers->clearCookie('REMEMBERME');
        }

        return $response;
    }
}
