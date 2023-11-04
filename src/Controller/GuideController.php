<?php

namespace App\Controller;

use App\Entity\Guide;
use App\Document\Item;
use App\Form\GuideType;
use App\Entity\RunesPage;
use App\Entity\ItemsGroup;
use App\Entity\OrdreItems;
use App\Service\ItemService;
use App\Service\RuneService;
use App\Service\GuideService;
use App\Entity\SortInvocateur;
use App\Service\ChampionService;
use App\Entity\EnsembleItemsGroups;
use App\Entity\AssociationsRunesBonus;
use App\Service\SortInvocateurService;
use App\Entity\AssociationsArbresRunes;
use Doctrine\ORM\EntityManagerInterface;
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
        EntityManagerInterface $entityManager
    ) {
        // Récupère la liste d'id des champions
        $championsData = $championService->getChampions();
        // URL pour récupérer les images
        $img_url = $championService->getChampionImageURL();

        // Création d'un Guide
        $guide = new Guide();
        $runesPage = new RunesPage();
        $guide->addGroupeRune($runesPage);

        // Création du formulaire
        $form = $this->createForm(GuideType::class, $guide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $groupesRunes = $form->get('groupeRunes')->getData();

            if (!$groupesRunes->isEmpty()) {
                // Ajout des runes dans la DB
                $guideService->runesHelper($form, $guide, $runesPage, $groupesRunes, $entityManager);
            }


            $entityManager->persist($guide);
            $entityManager->flush();

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
        ]);
    }

    #[Route('/groupe-sorts-invocateur', name: "get_groupe_sorts_invocateur")]
    public function getGroupeSortsInvocateur(
        SortInvocateurService $sortInvocateurService,
        ChampionService $championService,
    ) {
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

    #[Route('/groupe-items', name: "get_groupe_items")]
    public function getGroupeItems(
        ItemService $itemService,
        ChampionService $championService
    ) {
        // Liste des items
        $itemsList = $itemService->getItems();
        // URL pour récupérer les images
        $img_url = $championService->getChampionImageURL();

        // Création d'un Guide
        $guide = new Guide();
        $ensemble = new EnsembleItemsGroups();
        $itemsGroup = new ItemsGroup();
        $ensemble->addAssociationsEnsemblesItemsGroup($itemsGroup);
        $guide->addGroupeEnsemblesItem($ensemble);

        // Création du formulaire
        $form = $this->createForm(GuideType::class, $guide);

        return $this->render('guide/groupe-items.html.twig', [
            'form' => $form,
            'list_items' => $itemsList,
            'img_url' => $img_url
        ]);
    }

    #[Route('/groupe-competences', name: "get_groupe_competences")]
    public function getGroupeCompetences()
    {
        return $this->render('guide/groupe-competences.html.twig');
    }

    #[Route('/groupe-runes', name: "get_groupe_runes")]
    public function getGroupeRunes(
        ChampionService $championService,
        RuneService $runeService
    ) {
        // Get Runes
        $runesData = $runeService->getRunes();
        // URL pour récupérer les images
        $img_url = $championService->getChampionImageURL();

        // Création d'un guide
        $guide = new Guide();
        $runes = new RunesPage();
        $guide->addGroupeRune($runes);
        // Création du formulaire
        $form = $this->createForm(GuideType::class, $guide);

        return $this->render('guide/groupe-runes.html.twig', [
            'form' => $form,
            'img_url' => $img_url,
            'runes_data' => $runesData
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
