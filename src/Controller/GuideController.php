<?php

namespace App\Controller;

use App\Entity\Guide;
use App\Form\GuideType;
use App\Service\GuideService;
use App\Entity\SortInvocateur;
use App\Service\ChampionService;
use App\Service\SortInvocateurService;
use App\Service\RuneService;
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
        SortInvocateurService $sortInvocateurService,
        RuneService $runeService
    ) {
        // Récupère la liste d'id des champions
        $championsData = $championService->getChampions();
        // Liste des sorts d'invocateur
        $sortsInvocateurList = $sortInvocateurService->getSortsInvocateur();
        // Runes
        $runesData = $runeService->getRunes();
        // URL pour récupérer les images
        $img_url = $championService->getChampionImageURL();

        // Création d'un Guide
        $guide = new Guide();
        $sortInvocateur = new SortInvocateur();
        $guide->addGroupeSortsInvocateur($sortInvocateur);
        // Création du formulaire
        $form = $this->createForm(GuideType::class, $guide);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $champion = $form->get('champion')->getData();
            $groupesSortsInvocateur = $form->get('groupeSortsInvocateur')->getData();
            // Création du guide dans la db
            $guideService->createGuideFromForm($form->getData(), $champion, $groupesSortsInvocateur);

            return $this->redirectToRoute('new_guide');
        } else if ($form->isSubmitted() && !$form->isValid()) {
            // Si le formulaire est invalide
            $errors = []; // Tableau pour stocker les erreurs
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }

            return $this->json([
                'success' => false,
                'errors' => $errors,
            ]);
        }

        return $this->render('guide/create_guide.html.twig', [
            'form' => $form,
            'champions' => $championsData,
            'img_url' => $img_url,
            'list_sorts_invocateur' => $sortsInvocateurList,
            'runes' => $runesData
        ]);
    }

    #[Route('/load-groupe-sorts-invocateur', name: "load_groupe_sorts_invocateur")]
    public function loadGroupeSortsInvocateur(
        SortInvocateurService $sortInvocateurService,
        ChampionService $championService,
    ): Response {
        // Liste des sorts d'invocateur
        $sortsInvocateurList = $sortInvocateurService->getSortsInvocateur();

        // URL pour récupérer les images
        $img_url = $championService->getChampionImageURL();


        // Création d'un Guide
        $guide = new Guide();
        $sortInvocateur = new SortInvocateur();
        $guide->addGroupeSortsInvocateur($sortInvocateur);
        // Création du formulaire
        $form = $this->createForm(GuideType::class, $guide);


        return $this->render('guide/groupe-sorts-invocateur.html.twig', [
            'form' => $form,
            'list_sorts_invocateur' => $sortsInvocateurList,
            'img_url' => $img_url,
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
