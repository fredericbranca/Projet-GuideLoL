<?php

namespace App\Controller;

use App\Repository\EvaluationRepository;
use App\Repository\GuideRepository;
use App\Service\ChampionService;
use App\Service\ItemService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        ChampionService $championService,
        EvaluationRepository $evaluationRepository,
        ItemService $itemService
    ): Response {
        // URL des images
        $img_url = $championService->getChampionImageURL();
        // Dernier Champion
        $lastChampion = $championService->getChampion('Briar');

        // Listes des 10 guides les mieux notés
        $bestGuides = $evaluationRepository->getBestGuides();
        // Les derniers commentaires
        $commentaires = $evaluationRepository->getDerniersCommentaires();
        // Liste des items
        $itemsList = $itemService->getItems();

        return $this->render('home/index.html.twig', [
            'img_url' => $img_url,
            'best_guides' => $bestGuides,
            'commentaires' => $commentaires,
            'list_items' => $itemsList,
            'last_champ' => $lastChampion
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
