<?php

namespace App\Controller;

use App\Entity\DataChampion;
use App\Service\ChampionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    // Mettre à jour le nom des champions de la table data_champion dans la DB
    #[Route('/admin/data_champion_update', name: 'update_data_champion')]
    public function championUpdate(ChampionService $championService, EntityManagerInterface $em)
    {
        $champions = $championService->getChampionsName();
        foreach ($champions as $championName) {
            // Recherche du champion par son ID
            $champion = $em->getRepository(DataChampion::class)->findOneBy(['id' => $championName]);

            // Création du champion s'il n'existe pas encore
            if (!$champion) {
                $champion = new DataChampion;
                $champion->setId($championName);
                $em->persist($champion);
            }
        }

        $em->flush();

        return new Response('Nom des champions importés ou mis à jour avec succès !');
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
