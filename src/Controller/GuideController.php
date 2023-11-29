<?php

namespace App\Controller;

use App\Entity\Guide;
use App\Form\GuideType;
use App\Entity\RunesPage;
use App\Entity\ChoixItems;
use App\Entity\ItemsGroup;
use App\Service\ItemService;
use App\Service\RuneService;
use App\Form\GuideFiltreType;
use App\Service\GuideService;
use App\Entity\SortInvocateur;
use App\Entity\CompetencesGroup;
use App\Service\ChampionService;
use App\Service\CompetenceService;
use App\Entity\EnsembleItemsGroups;
use App\Repository\GuideRepository;
use App\Service\SortInvocateurService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

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


    // Route pour créer un guide
    // Route pour éditer un guide existant
    #[Route('/guide/new', name: 'new_guide')]
    #[Route('/guide/{id}/edit', name: 'edit_guide')]
    public function newOrEditGuide(
        Request $request,
        ChampionService $championService,
        GuideService $guideService,
        EntityManagerInterface $entityManager,
        RuneService $runeService,
        CompetenceService $competenceService,
        ItemService $itemService,
        Guide $guide = null
    ): Response {

        // Si c'est un nouveau guide, on le créé
        $isNewGuide = ($guide === null);
        if ($isNewGuide) {
            $guide = new Guide();
        }

        // Autorisation d'accès aux routes (appelé ici car si c'est un nouveau guide, les autorisations d'accès sont différentes)
        $this->denyAccessUnlessGranted('guide_edit', $guide, "Vous ne pouvez qu'éditer vos propre guide.");

        // Initialise la liste des champions
        $championsData = $championService->getChampions();
        // Initialise l'URL d'accès aux images
        $img_url = $championService->getChampionImageURL();

        // Création du formulaire
        $form = $this->createForm(GuideType::class, $guide, ['champion_id' => null]);
        // Traitement de la requête et hydratation conditionnelle de l'objet
        $form->handleRequest($request);

        // Si le formulaire a été soumit et est valide
        if ($form->isSubmitted() && $form->isValid()) {

            $guideData = $request->request->All();
            $groupesRunes = $form->get('groupeRunes')->getData();

            if (!$groupesRunes->isEmpty()) {
                // Fonction pour trier et persist les runes
                $guideService->runesHelper($form, $guide, $groupesRunes, $entityManager);
            }

            $groupesCompetences = $form->get('groupesCompetences')->getData();

            if (!$groupesCompetences->isEmpty() && !empty($guideData['guide']['groupesCompetences'])) {
                $groupesCompetencesData = $guideData['guide']['groupesCompetences'];
                // Fonction pour trier et persist les compétences
                $guideService->competencesHelper($guide, $groupesCompetences, $groupesCompetencesData, $entityManager);
            }

            // On lie le guide à l'utilisateur qui le créé
            $user = $this->security->getUser();
            $guide->setUser($user);

            // Si c'est l'édition d'un guide, on ajoute la date de modification
            if (!$isNewGuide) {
                $guide->setModifiedAt(new \DateTimeImmutable());
            }

            // Persist et fush le guide
            $entityManager->persist($guide);
            $entityManager->flush();

            // Redirection vers la vue pour afficher le Guide qui vient d'etre créé ou mise à jour
            return $this->redirectToRoute('get_guide_byId', ['id' => $guide->getId()]);

            // Si aucun formulaire n'est soumis
        } else {

            // Obtenir les données du formulaire
            // Null si pas de soumission
            $guideData = $request->request->All();

            // Initialisation des variables des gestions des erreurs
            $errorsSections = [];
            $errorsSections = [
                'groupeSortsInvocateur' => false,
                'groupeRunes' => false,
                'groupeEnsemblesItems' => false,
                'groupesCompetences' => false,
            ];

            // Si le formulaire est invalide
            if ($form->isSubmitted() && !$form->isValid()) {

                // Si l'erreur vient d'une section, on met l'état de la section à true
                foreach ($form->getErrors(true) as $error) {
                    $propertyPath = $error->getCause()->getPropertyPath();

                    if (strpos($propertyPath, 'groupeSortsInvocateur') !== false) {
                        $errorsSections['groupeSortsInvocateur'] = true;
                    } elseif (strpos($propertyPath, 'groupeRunes') !== false) {
                        $errorsSections['groupeRunes'] = true;
                    } elseif (strpos($propertyPath, 'groupeEnsemblesItems') !== false) {
                        $errorsSections['groupeEnsemblesItems'] = true;
                    } elseif (strpos($propertyPath, 'groupesCompetences') !== false) {
                        $errorsSections['groupesCompetences'] = true;
                    }
                }

                // Renvoie à la vue correspondante avec les erreurs et le formulaire rempli avec les informations déjà entrées
                return $this->render('guide/' . ($isNewGuide ? 'create_guide' : 'edit_guide') . '.html.twig', [
                    'form' => $form,
                    'guideData' => $guideData,
                    'champions' => $championsData,
                    'img_url' => $img_url,
                    'errors' => $errorsSections
                ]);
            }
        }

        if ($isNewGuide) {
            return $this->render('guide/create_guide.html.twig', [
                'form' => $form,
                'guideData' => $guideData,
                'champions' => $championsData,
                'img_url' => $img_url,
                'errors' => $errorsSections
            ]);
        } else {
            // Infos du niveaux des compétences
            $infoNiveauxSorts = $competenceService->getNiveauxParSorts($guide);
            // Infos des runes sélectionnées
            $infosRunes = $runeService->getRunesSelection($guide);
            // Ordre des items
            $infosItems = $itemService->getInfosItems($guide);

            return $this->render('guide/edit_guide.html.twig', [
                'form' => $form,
                'guideData' => $guideData,
                'champions' => $championsData,
                'img_url' => $img_url,
                'infos_sorts' => $infoNiveauxSorts,
                'infos_runes' => $infosRunes,
                'infos_items' => $infosItems,
                'errors' => $errorsSections
            ]);
        }
    }

    // Route pour afficher un Guide avec son id
    #[Route('/guide/{id}', name: 'get_guide_byId')]
    public function getGuide(
        Guide $guide,
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
    #[Route('/groupe-sorts-invocateur/edit/{id}', name: "edit_groupe_sorts_invocateur")]
    public function createOrEditGroupeSortsInvocateur(Request $request, Guide $guide = null): Response
    {
        // Récupérer les données soumises
        $data = json_decode($request->getContent(), true);
        if ($data != null) {
            $data = $data['guide']['groupeSortsInvocateur'];
        }

        $isNewGuide = ($guide === null);

        if ($isNewGuide) {
            $guide = new Guide();
            $guide->addGroupeSortsInvocateur(new SortInvocateur());
        }

        // Appel de la méthode globale pour créer le formulaire
        $formInfo = $this->createGuideForm($guide, 'sorts', !$isNewGuide ? null : ($data ? null : 0), null, $data);

        if ($isNewGuide && $data == null) {
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
    public function createOrEditEnsembleItems(Request $request, int $index = null, int $idGuide = null): Response
    {
        $index = null;

        // Récupérer les données soumises
        $data = json_decode($request->getContent(), true);
        if ($data != null) {
            $data = $data['guide']['groupeEnsemblesItems'];
        }

        // Initialisation ou récupération du Guide
        $guide = $idGuide ? $this->entityManager->getRepository(Guide::class)->find($idGuide) : new Guide();
        if (!$idGuide) {
            $ensemble = new EnsembleItemsGroups();
            $ensemble->addAssociationsEnsemblesItemsGroup(new ItemsGroup());
            $guide->addGroupeEnsemblesItem($ensemble);
            if ($data == null) {
                $index = 0;
            }
        }

        // Appel de la méthode globale pour créer le formulaire
        $formInfo = $this->createGuideForm($guide, 'ensembleItems', $index, null, $data);

        if (!$idGuide && $data == null) {
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
        $ensemble->addAssociationsEnsemblesItemsGroup(new ItemsGroup());
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
        Request $request
    ): Response {

        // Récupérer les données soumises
        $data = json_decode($request->getContent(), true);
        if ($data != null) {
            $data = $data['guide']['groupesCompetences'];
        }

        $index = null;

        // Initialisation ou récupération du Guide
        $guide = $idGuide ? $this->entityManager->getRepository(Guide::class)->find($idGuide) : new Guide();

        if (!$idGuide) {
            $guide->addGroupesCompetence(new CompetencesGroup());
            if ($data == null) {
                $index = 0;
            }
        }

        // Appel de la méthode globale pour créer le formulaire
        $formInfo = $this->createGuideForm($guide, 'competences', $index, $idChamp, $data);

        // Récupères les compétences du Champion que l'user a choisit
        $championData = $championService->getChampionSpells($idChamp);

        if (!$idGuide && $data == null) {
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
    public function createOrEditGroupeRunes(Request $request, int $index = null, int $idGuide = null): Response
    {
        // Récupérer les données soumises
        $data = json_decode($request->getContent(), true);
        if ($data != null) {
            $data = $data['guide']['groupeRunes'];
        }

        $index = null;

        // Initialisation ou récupération du Guide
        $guide = $idGuide ? $this->entityManager->getRepository(Guide::class)->find($idGuide) : new Guide();
        if (!$idGuide) {
            $guide->addGroupeRune(new RunesPage());
            if ($data == null) {
                $index = 0;
            }
        }

        // Appel de la méthode globale pour créer le formulaire
        $formInfo = $this->createGuideForm($guide, 'runes', $index, null, $data);

        if (!$idGuide && $data == null) {
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

    #[Route('/guides', name: 'app_guide', methods: ["POST", "GET"])]
    public function index(
        GuideRepository $guideRepository,
        ChampionService $championService,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        // Prépare les données pour le formulaire (avec les données initiales)
        $initialData = [];
        if ($request->query->has('champion')) {
            $initialData['champion'] = $request->query->get('champion');
        }
        if ($request->query->has('role')) {
            $initialData['role'] = $request->query->get('role');
        }

        // Crée le formulaire de filtre
        $filtreForm = $this->createForm(GuideFiltreType::class, $initialData);

        // Traite le formulaire
        $filtreForm->handleRequest($request);

        if ($filtreForm->isSubmitted() && $filtreForm->isValid()) {
            $filtres = $filtreForm->getData();

            // URL de redirection
            $redirectionUrl = $this->generateUrl('app_guide', [
                'champion' => $filtres['champion'] ?? null,
                'role' => $filtres['role'] ?? null
            ]);

            // Redirige vers l'URL
            return $this->redirect($redirectionUrl);
        }

        // Utilise les données du formulaire pour filtrer
        $filtres = $filtreForm->isSubmitted() && $filtreForm->isValid() ? $filtreForm->getData() : $initialData;

        // Récupère les guides + prépare l'affichage avec paginator pour la pagination
        $guides = $paginator->paginate(
            $guideRepository->findByDateWithFilters($filtres),
            $request->query->getInt('page', 1),
            5,
            ['pageParameterName' => 'page']
        );

        // URL pour récupérer les images
        $img_url = $championService->getChampionImageURL();

        // Prépare les données pour la vue
        return $this->render('guide/index.html.twig', [
            'guides' => $guides,
            'img_url' => $img_url,
            'filtre_form' => $filtreForm->createView(),
        ]);
    }

    // Route pour qu'un utilisateur supprime un guide qu'il a créé par son id
    #[Route('/guide/{id}/delete', name: 'user_delete_guide', methods: ["POST"])]
    public function deleteGuide(
        EntityManagerInterface $em,
        Guide $guide,
        Request $request,
        CsrfTokenManagerInterface $csrfTokenManager
    ): Response {
        // Vérification de jeton CSRF
        $csrfToken = new CsrfToken('delete_guide', $request->request->get('_csrf_token'));
        if (!$csrfTokenManager->isTokenValid($csrfToken)) {
            throw new InvalidCsrfTokenException();
        }

        $this->denyAccessUnlessGranted('guide_delete', $guide, "Vous ne pouvez supprimer que vos guides.");

        $em->remove($guide);
        $em->flush();

        return $this->redirectToRoute('app_guide');
    }


    // Liste des champions
    #[Route('/champions', name: 'app_champions', methods: ["GET"])]
    public function getChampions(
        ChampionService $championService
    ): Response {
        // URL pour récupérer les images
        $img_url = $championService->getChampionImageURL();

        $champions = $championService->getChampions();

        return $this->render('guide/champions.html.twig', [
            'champions' => $champions,
            'img_url' => $img_url
        ]);
    }

    // Fonction commune pour créer le formulaire et préparer les données pour le rendu
    private function createGuideForm(Guide $guide, string $type, ?int $index, ?string $idChamp, $dataForm): array
    {
        // Création du formulaire
        $form = $this->createForm(GuideType::class, $guide, ['champion_id' => $idChamp]);

        // Logique pour sélectionner la bonne partie du formulaire en fonction du type et de l'index
        $childView = null;
        switch ($type) {
            case 'sorts':
                $dataForm ? $form->submit(['groupeSortsInvocateur' => $dataForm]) : '';

                // Générer la vue du formulaire
                $formView = $form->createView();

                $childView = $formView->children['groupeSortsInvocateur'] ?? null;
                if ($index !== null) {
                    $childView = $childView[$index] ?? null;
                }
                $list = $this->sortInvocateurService->getSortsInvocateur();
                break;
            case 'ensembleItems':
                $dataForm ? $form->submit(['groupeEnsemblesItems' => $dataForm]) : '';

                // Générer la vue du formulaire
                $formView = $form->createView();

                $childView = $formView->children['groupeEnsemblesItems'] ?? null;
                if ($index !== null) {
                    $childView = $childView[$index] ?? null;
                }
                $list = $this->itemService->getItems();
                break;
            case 'competences':
                $dataForm ? $form->submit(['groupesCompetences' => $dataForm]) : '';

                // Générer la vue du formulaire
                $formView = $form->createView();

                $childView = $formView->children['groupesCompetences'] ?? null;
                if ($index !== null) {
                    $childView = $childView[$index] ?? null;
                }
                $list = null;
                break;
            case 'runes':
                $dataForm ? $form->submit(['groupeRunes' => $dataForm]) : '';

                // Générer la vue du formulaire
                $formView = $form->createView();

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
