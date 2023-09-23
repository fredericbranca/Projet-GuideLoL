<?php

namespace App\Controller;

use App\Form\GuideType;
use App\Service\GuideService;
use App\Service\ChampionService;
use App\Repository\DataChampionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GuideController extends AbstractController
{
    // Route qui mène vers la création d'un guide et redirige vers le guide créé s'il est validé
    #[Route('/guide/new', name: 'new_guide')]
    public function new(Request $request, ChampionService $championService, GuideService $guideService, DataChampionRepository $dataChampionRepository)
    {
        // Récupère la liste d'id des champions
        $championsData = $championService->getChampions();
        // Nom des champions
        $championsList = $championService->getChampionsIdName();
        // dd($championsList);
        // URL pour récupérer les images
        $img_url = $championService->getChampionImageURL();

        //Création du formulaire avec le GuideType
        $form = $this->createForm(GuideType::class, null, [
            'champions' => $championsList
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $champion = $form->get('champion')->getData();

            // Vérifie si la value du champ champion est valide
            $championExiste = $dataChampionRepository->findOneBy(['id' => $champion]);
            if (!$championExiste) {
                return $this->redirectToRoute('new_guide');
            }

            // Création du guide
            $guideService->createGuideFromForm($form->getData(), $champion);

            return $this->redirectToRoute('new_guide');
        }

        return $this->render('guide/create_guide.html.twig', [
            'form' => $form,
            'champions' => $championsData,
            'img_url' => $img_url
        ]);
    }

    #[Route('/guide', name: 'app_guide')]
    public function index(): Response
    {
        return $this->render('guide/index.html.twig', [
            'controller_name' => 'GuideController',
        ]);
    }
}
