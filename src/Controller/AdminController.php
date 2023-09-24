<?php

namespace App\Controller;

use App\Entity\DataChampion;
use App\Entity\DataSortInvocateur;
use App\Service\ChampionService;
use App\Service\SortInvocateurService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    // Ajouter ou mettre à jour l'ID des champions de la table data_champion dans la DB
    #[Route('/admin/data_champion_update', name: 'update_data_champion')]
    public function championUpdate(ChampionService $championService, EntityManagerInterface $em)
    {
        $champions = $championService->getChampionsName();
        foreach ($champions as $championName) {
            // Recherche du champion par son ID
            $championId = $em->getRepository(DataChampion::class)->findOneBy(['id' => $championName]);

            // Création du champion s'il n'existe pas encore
            if (!$championId) {
                $championId = new DataChampion;
                $championId->setId($championName);
                $em->persist($championId);
            }
        }

        $em->flush();

        return new Response('Nom des champions importés ou mis à jour avec succès !');
    }

    // Ajouter ou mettre à jour les ID des sorts d'invocateur de la table data_sort_invocateur dans la DB
    #[Route('/admin/data_sort_invocateur_update', name: 'update_data_sort_invocateur_update')]
    public function sortsInvocateurUpdate(SortInvocateurService $sortInvocateurService, EntityManagerInterface $em)
    {
        $sortsInvocateur = $sortInvocateurService->getSortsInvocateurId();
        foreach ($sortsInvocateur as $sortInvocateur) {
            // Recherche d'un sort d'invocateur' par son ID
            $sortInvocateurId = $em->getRepository(DataSortInvocateur::class)->findOneBy(['id' => $sortInvocateur]);

            // Création du sort s'il n'existe pas encore
            if (!$sortInvocateurId) {
                $sortInvocateurId = new DataSortInvocateur;
                $sortInvocateurId->setId($sortInvocateur);
                $em->persist($sortInvocateurId);
            }
        }

        $em->flush();

        return new Response('Sorts d\'invocateur importés ou mis à jour avec succès !');
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
