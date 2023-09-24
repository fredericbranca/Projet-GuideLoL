<?php

namespace App\Controller;

use App\Entity\Guide;
use App\Form\GuideType;
use App\Service\GuideService;
use App\Entity\SortInvocateur;
use App\Service\ChampionService;
use App\Service\SortInvocateurService;
use App\Repository\DataChampionRepository;
use App\Repository\DataSortInvocateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GuideController extends AbstractController
{
    // Route qui mène vers la création d'un guide et redirige vers le guide créé s'il est validé
    #[Route('/guide/new', name: 'new_guide')]
    public function new(
        Request $request,
        ChampionService $championService,
        GuideService $guideService,
        DataChampionRepository $dataChampionRepository,
        SortInvocateurService $sortInvocateurService,
        DataSortInvocateurRepository $dataSortInvocateurRepository
    ) {
        // Récupère la liste d'id des champions
        $championsData = $championService->getChampions();
        // Nom des champions
        $championsList = $championService->getChampionsIdName();
        // URL pour récupérer les images
        $img_url = $championService->getChampionImageURL();
        // Liste des sorts d'invocateur
        $sortsInvocateurList = $sortInvocateurService->getSortsInvocateur();

        // Création d'un Guide
        $guide = new Guide();
        $sortInvocateur = new SortInvocateur();
        $guide->addGroupeSortsInvocateur($sortInvocateur);
        // Création du formulaire
        $form = $this->createForm(GuideType::class, $guide, [
            'champions' => $championsList
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $champion = $form->get('champion')->getData();
            $groupesSortsInvocateur = $form->get('groupeSortsInvocateur')->getData();

            // Vérifie si la value du champ champion est valide
            $championExiste = $dataChampionRepository->findOneBy(['id' => $champion]);
            if (!$championExiste) {
                return new Response('Champion invalide');
            }
            // Vérifie si la value des sorts d'invocateurs est valide
            foreach ($groupesSortsInvocateur as $sortsInvocateur) {
                $sortsInvocateurName = $sortInvocateur->getChoixSortInvocateur();
                foreach ($sortsInvocateurName as $sortInvocateurName) {
                    $sortExiste = $dataSortInvocateurRepository->findOneBy(['id' => $sortInvocateurName]);
                    if (!$sortExiste) {
                        return new Response('Sort d\'invocateur invalide');
                    }
                }
            }

            // Création du guide dans la db
            $guideService->createGuideFromForm($form->getData(), $champion, $groupesSortsInvocateur);

            return $this->redirectToRoute('new_guide');
        }

        return $this->render('guide/create_guide.html.twig', [
            'form' => $form,
            'champions' => $championsData,
            'img_url' => $img_url,
            'list_sorts_invocateur' => $sortsInvocateurList
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
