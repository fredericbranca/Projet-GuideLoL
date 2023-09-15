<?php

namespace App\Controller;

use App\Entity\DataChampion;
use App\Entity\Guide;
use App\Form\GuideType;
use App\HttpClient\LoLHttpClient;
use App\Repository\DataChampionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GuideController extends AbstractController
{
    // Route qui mène vers la création d'un guide et redirige vers le guide créé s'il est validé
    #[Route('/guide/new', name: 'new_guide')]
    public function new(Request $request, EntityManagerInterface $entityManager, LoLHttpClient $lol, DataChampionRepository $dataChampionRepository) {

        // Récupère la liste d'id des champions
        $championsData = $lol->getChampions();
        $championsData = json_decode($championsData, true);

        // dd($championsData["hydra:member"]);

        foreach ($championsData["hydra:member"] as $champion) {
            $name[$champion['name']] = $champion['id'];
            $image[$champion['image']["full"]] = $champion['id'];
        }
        // dd($image);

        //Création du formulaire avec le GuideType
        $form = $this->createForm(GuideType::class, null, [
            'champions' => $name
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Recupère la value du champ champion
            $champion = $request->request->get('champion');

            // Vérifie si la value du champ champion est valide
            $championExiste = $dataChampionRepository->findOneBy(['id' => $champion]);
            if (!$championExiste) {
                return $this->redirectToRoute('new_guide');
            }

            // Création du guide
            $guide = new Guide();
            $guide = $form->getData();
            $guide->setChampion($champion);

            $entityManager->persist($guide);
            $entityManager->flush();

            return $this->redirectToRoute('new_guide');
        }

        return $this->render('guide/create_guide.html.twig', [
            'form' => $form,
            'champions' => $championsData['hydra:member']
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
