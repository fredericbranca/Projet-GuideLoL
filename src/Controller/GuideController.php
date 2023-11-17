<?php

namespace App\Controller;

use App\Entity\ChoixItems;
use App\Entity\Guide;
use App\Form\GuideType;
use App\Entity\RunesPage;
use App\Entity\ItemsGroup;
use App\Service\ItemService;
use App\Service\RuneService;
use App\Service\GuideService;
use App\Entity\SortInvocateur;
use App\Entity\CompetencesGroup;
use App\Service\ChampionService;
use App\Service\CompetenceService;
use App\Entity\EnsembleItemsGroups;
use App\Repository\GuideRepository;
use App\Service\SortInvocateurService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GuideController extends AbstractController
{
    private $sortInvocateurService;
    private $championService;
    private $itemService;
    private $runeService;
    private $entityManager;
    private $security;

    public function __construct(
        SortInvocateurService $sortInvocateurService,
        ChampionService $championService,
        ItemService $itemService,
        RuneService $runeService,
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        $this->sortInvocateurService = $sortInvocateurService;
        $this->championService = $championService;
        $this->itemService = $itemService;
        $this->entityManager = $entityManager;
        $this->runeService = $runeService;
        $this->security = $security;
    }


    // Route qui mène vers la création d'un guide et redirige vers le guide créé s'il est validé
    #[Route('/guide/new', name: 'new_guide')]
    #[Route('/guide/{idGuide}/edit', name: 'edit_guide')]
    public function new(
        Request $request,
        ChampionService $championService,
        GuideService $guideService,
        EntityManagerInterface $entityManager,
        RuneService $runeService,
        CompetenceService $competenceService,
        int $idGuide = null
    ): Response {
        $user = $this->security->getUser();

        // Récupère le guide avec l'id ou en créé un s'il n'existe pas
        $guide = $idGuide ? $entityManager->getRepository(Guide::class)->find($idGuide) : new Guide();

        if ($idGuide && $guide->getUser() !== $user) {
            throw new AccessDeniedException('Vous ne pouvez pas éditer le guide des autres utilisateurs.');
        }

        // Récupère la liste d'id des champions
        $championsData = $championService->getChampions();
        // URL pour récupérer les images
        $img_url = $championService->getChampionImageURL();
        
        // dump($request->request->All());die;
        // Création du formulaire
        $form = $this->createForm(GuideType::class, $guide, ['champion_id' => null]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $guideData = $request->request->All();
            dump($guideData); die;
            $groupesRunes = $form->get('groupeRunes')->getData();

            if (!$groupesRunes->isEmpty()) {
                // Persist les runes
                $guideService->runesHelper($form, $guide, $groupesRunes, $entityManager);
            }

            $groupesCompetences = $form->get('groupesCompetences')->getData();

            if (!$groupesCompetences->isEmpty() && !empty($guideData['guide']['groupesCompetences'])) {
                $groupesCompetencesData = $guideData['guide']['groupesCompetences'];
                // Persist les compétences
                $guideService->competencesHelper($guide, $groupesCompetences, $groupesCompetencesData, $entityManager);
            }

            $guide->setUser($user);

            if ($idGuide) {
                $guide->setModifiedAt(new \DateTimeImmutable());
            }

            $entityManager->persist($guide);
            $entityManager->flush();

            return $this->redirectToRoute('get_guide_byId', ['idGuide' => $guide->getId()]);
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

        if (!$idGuide) {
            return $this->render('guide/create_guide.html.twig', [
                'form' => $form,
                'champions' => $championsData,
                'img_url' => $img_url,
            ]);
        } else {
            // Infos du niveaux des compétences
            $infoNiveauxSorts = $competenceService->getNiveauxParSorts($guide);
            // Infos des runes sélectionnées
            $infosRunes = $runeService->getRunesSelection($guide);

            return $this->render('guide/edit_guide.html.twig', [
                'form' => $form,
                'champions' => $championsData,
                'img_url' => $img_url,
                'infos_sorts' => $infoNiveauxSorts,
                'infos_runes' => $infosRunes
            ]);
        }
    }

    // Route pour afficher un Guide avec son id
    #[Route('/guide/{idGuide}', name: 'get_guide_byId')]
    public function getGuide(
        int $idGuide,
        EntityManagerInterface $em,
        ChampionService $championService,
        SortInvocateurService $sortInvocateurService,
        ItemService $itemService,
        RuneService $runeService
    ): Response {
        // URL pour récupérer les images
        $img_url = $championService->getChampionImageURL();
        // Liste des sorts d'invocateur
        $sortsInvocateurList = $sortInvocateurService->getSortsInvocateur();
        // Liste des items
        $itemsList = $itemService->getItems();
        // Liste des runes
        $runesData = $runeService->getRunesIds();

        $guide = $em->getRepository(Guide::class)->find($idGuide);

        if (!$guide) {
            throw $this->createNotFoundException('Guide not found.');
        }

        // Récupère les data du champion
        $championData = $championService->getChampion($guide->getChampion());

        return $this->render('guide/afficher_guide.html.twig', [
            'guide' => $guide,
            'img_url' => $img_url,
            'list_sorts_invocateur' => $sortsInvocateurList,
            'list_items' => $itemsList,
            'runes_data' => $runesData,
            'champion' => $championData
        ]);
    }


    // Routes vers les composants pour la création et l'édition de Guide

    // Route composant formulaire pour ajouter un groupe de Sorts d'Invocateur ou sa version avec les données du guide
    #[Route('/groupe-sorts-invocateur/create', name: "create_groupe_sorts_invocateur")]
    #[Route('/groupe-sorts-invocateur/edit/{idGuide}', name: "edit_groupe_sorts_invocateur")]
    public function createOrEditGroupeSortsInvocateur(int $idGuide = null): Response
    {
        $index = null;

        // Initialisation ou récupération du Guide
        $guide = $idGuide ? $this->entityManager->getRepository(Guide::class)->find($idGuide) : new Guide();
        if (!$idGuide) {
            $guide->addGroupeSortsInvocateur(new SortInvocateur());
            $index = 0;
        }

        // Appel de la méthode globale pour créer le formulaire
        $formInfo = $this->createGuideForm($guide, 'sorts', $index, null);

        if (!$idGuide) {
            return $this->render('guide/groupe-sorts-invocateur.html.twig', [
                'form' => $formInfo['form'],
                'list_sorts_invocateur' => $formInfo['list'],
                'img_url' => $formInfo['img_url'],
            ]);
        } else {
            // Affichage de la vue avec les données du formulaire
            return $this->render('guide/groupe-sorts-invocateur.html.twig', [
                'forms' => $formInfo['form'],
                'list_sorts_invocateur' => $formInfo['list'],
                'img_url' => $formInfo['img_url'],
            ]);
        }
    }

    // Route composant formulaire pour ajouter un Ensemble de groupe d'item ou sa version avec les données du guide
    #[Route('/ensemble-items/create', name: "create_ensemble_items")]
    #[Route('/ensemble-items/edit/{idGuide}', name: "edit_ensemble_items")]
    public function createOrEditEnsembleItems(int $index = null, int $idGuide = null): Response
    {
        $index = null;

        // Initialisation ou récupération du Guide
        $guide = $idGuide ? $this->entityManager->getRepository(Guide::class)->find($idGuide) : new Guide();

        if (!$idGuide) {
            $ensemble = new EnsembleItemsGroups();
            $itemsGroup = new ItemsGroup();
            $itemsGroup->addChoixItems(new ChoixItems());
            $ensemble->addAssociationsEnsemblesItemsGroup($itemsGroup);
            $guide->addGroupeEnsemblesItem($ensemble);
            $index = 0;
        }

        // Appel de la méthode globale pour créer le formulaire
        $formInfo = $this->createGuideForm($guide, 'ensembleItems', $index, null);

        if (!$idGuide) {
            return $this->render('guide/ensemble-items.html.twig', [
                'form' => $formInfo['form'],
                'list_items' => $formInfo['list'],
                'img_url' => $formInfo['img_url'],
            ]);
        } else {
            // Affichage de la vue avec les données du formulaire
            return $this->render('guide/ensemble-items.html.twig', [
                'forms' => $formInfo['form'],
                'list_items' => $formInfo['list'],
                'img_url' => $formInfo['img_url'],
            ]);
        }
    }

    // Route composant formulaire pour ajouter un groupe d'Items
    #[Route('/groupe-items/create', name: "create_groupe_items")]
    public function createGroupeItems(
        ItemService $itemService,
        ChampionService $championService
    ): Response {
        // Liste des items
        $itemsList = $itemService->getItems();
        // URL pour récupérer les images
        $img_url = $championService->getChampionImageURL();
        // Création d'un Guide
        $guide = new Guide();

        // Création du groupe d'items
        $ensemble = new EnsembleItemsGroups();
        $itemsGroup = new ItemsGroup();
        $itemsGroup->addChoixItems(new ChoixItems());
        $ensemble->addAssociationsEnsemblesItemsGroup($itemsGroup);
        $guide->addGroupeEnsemblesItem($ensemble);

        // Création du formulaire
        $form = $this->createForm(GuideType::class, $guide, ['champion_id' => null]);
        $formView = $form->createView();

        // Je récupère le formulaire pour le groupe d'items spécifique à l'indexSet et indexGroup
        $ensembleGroupeItemsFormView = $formView
            ->children['groupeEnsemblesItems'][0]
            ->children['associationsEnsemblesItemsGroups'][0] ?? null;

        return $this->render('guide/groupe-items.html.twig', [
            'form' => $ensembleGroupeItemsFormView,
            'list_items' => $itemsList,
            'img_url' => $img_url
        ]);
    }


    // Route composant formulaire pour ajouter un Groupe de Compétences
    #[Route('/groupe-competences/create/{idChamp}', name: "create_groupe_competences")]
    #[Route('/groupe-competences/edit/{idChamp}/{idGuide}', name: "edit_groupe_competences")]
    public function createOrEditGroupeCompetences(
        string $idChamp,
        int $idGuide = null,
        ChampionService $championService,
    ): Response {
        $index = null;

        // Initialisation ou récupération du Guide
        $guide = $idGuide ? $this->entityManager->getRepository(Guide::class)->find($idGuide) : new Guide();

        if (!$idGuide) {
            $guide->addGroupesCompetence(new CompetencesGroup());
            $index = 0;
        }

        // Appel de la méthode globale pour créer le formulaire
        $formInfo = $this->createGuideForm($guide, 'competences', $index, $idChamp);

        // Récupères les compétences du Champion que l'user a choisit
        $championData = $championService->getChampionSpells($idChamp);

        if (!$idGuide) {
            return $this->render('guide/groupe-competences.html.twig', [
                'form' => $formInfo['form'],
                'champ' => $championData,
                'img_url' => $formInfo['img_url'],
            ]);
        } else {
            // Affichage de la vue avec les données du formulaire
            return $this->render('guide/groupe-competences.html.twig', [
                'forms' => $formInfo['form'],
                'champ' => $championData,
                'img_url' => $formInfo['img_url']
            ]);
        }
    }

    // Route composant formulaire pour ajouter un groupe de Runes ou sa version avec les données du guide
    #[Route('/groupe-runes/create', name: "create_groupe_runes")]
    #[Route('/groupe-runes/edit/{idGuide}', name: "edit_groupe_runes")]
    public function createOrEditGroupeRunes(int $index = null, int $idGuide = null): Response
    {
        $index = null;

        // Initialisation ou récupération du Guide
        $guide = $idGuide ? $this->entityManager->getRepository(Guide::class)->find($idGuide) : new Guide();
        if (!$idGuide) {
            $guide->addGroupeRune(new RunesPage());
            $index = 0;
        }

        // Appel de la méthode globale pour créer le formulaire
        $formInfo = $this->createGuideForm($guide, 'runes', $index, null);

        if (!$idGuide) {
            return $this->render('guide/groupe-runes.html.twig', [
                'form' => $formInfo['form'],
                'runes_data' => $formInfo['list'],
                'img_url' => $formInfo['img_url'],
            ]);
        } else {
            // Affichage de la vue avec les données du formulaire
            return $this->render('guide/groupe-runes.html.twig', [
                'forms' => $formInfo['form'],
                'runes_data' => $formInfo['list'],
                'img_url' => $formInfo['img_url'],
            ]);
        }
    }

    #[Route('/guides', name: 'app_guide')]
    public function index(
        GuideRepository $guideRepository
    ): Response {
        $guides = $guideRepository->findAll();

        return $this->render('guide/index.html.twig', [
            'guides' => $guides,
        ]);
    }

    #[Route('/guide/{idGuide}/delete', name: 'delete_guide')]
    public function deleteGuide(
        EntityManagerInterface $em,
        int $idGuide
    ): Response {
        $user = $this->security->getUser();

        if (!$user) {
            return new Response('Non autorisé', Response::HTTP_UNAUTHORIZED);
        }

        $guide = $em->getRepository(Guide::class)->find($idGuide);

        if (!$guide) {
            throw $this->createNotFoundException('Ce guide n\'existe pas.');
        }

        if ($guide->getUser() !== $user) {
            throw new AccessDeniedException('Action non autorisé.');
        }

        $em->remove($guide);
        $em->flush();

        return $this->redirectToRoute('app_guide');
    }



    #[Route('/champions', name: 'app_champions')]
    public function getChampions(): Response
    {
        return $this->render('guide/champions.html.twig', [
            'controller_name' => 'GuideController',
        ]);
    }

    // Fonction commune pour créer le formulaire et préparer les données pour le rendu
    private function createGuideForm(Guide $guide, string $type, ?int $index, ?string $idChamp): array
    {
        // Création du formulaire
        $form = $this->createForm(GuideType::class, $guide, ['champion_id' => $idChamp]);

        // Générer la vue du formulaire
        $formView = $form->createView();

        // Logique pour sélectionner la bonne partie du formulaire en fonction du type et de l'index
        $childView = null;
        switch ($type) {
            case 'sorts':
                $childView = $formView->children['groupeSortsInvocateur'] ?? null;
                if ($index !== null) {
                    $childView = $childView[$index] ?? null;
                }
                $list = $this->sortInvocateurService->getSortsInvocateur();
                break;
            case 'ensembleItems':
                $childView = $formView->children['groupeEnsemblesItems'] ?? null;
                if ($index !== null) {
                    $childView = $childView[$index] ?? null;
                }
                $list = $this->itemService->getItems();
                break;
            case 'competences':
                $childView = $formView->children['groupesCompetences'] ?? null;
                if ($index !== null) {
                    $childView = $childView[$index] ?? null;
                }
                $list = null;
                break;
            case 'runes':
                $childView = $formView->children['groupeRunes'] ?? null;
                if ($index !== null) {
                    $childView = $childView[$index] ?? null;
                }
                $list = $this->runeService->getRunes();
                break;
        }

        $img_url = $this->championService->getChampionImageURL();

        return [
            'form' => $childView,
            'list' => $list,
            'img_url' => $img_url,
        ];
    }
}
