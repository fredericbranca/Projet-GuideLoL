<?php

namespace App\Controller;

use App\Entity\DataChampion;
use App\Entity\DataRune;
use App\Entity\DataSortInvocateur;
use App\Entity\DataStatistiqueBonus;
use App\Service\ChampionService;
use App\Service\RuneService;
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
        // Supprimer toutes les entrées existantes de la table des champions
        $em->createQuery('DELETE FROM App\Entity\DataChampion')->execute();

        // Récupérer les champions depuis le service
        $champions = $championService->getChampionsName();

        // Ajouter chaque champion à la base de données
        foreach ($champions as $championName) {
            $championId = new DataChampion;
            $championId->setId($championName);
            $em->persist($championId);
        }

        // Flush les changements à la base de données
        $em->flush();

        return new Response('Nom des champions importés ou mis à jour avec succès !');
    }

    // Ajouter ou mettre à jour les ID des sorts d'invocateur de la table data_sort_invocateur dans la DB
    #[Route('/admin/data_sort_invocateur_update', name: 'update_data_sort_invocateur')]
    public function sortsInvocateurUpdate(SortInvocateurService $sortInvocateurService, EntityManagerInterface $em)
    {
        // Supprimer toutes les entrées existantes de la table des sorts d'invocateur
        $em->createQuery('DELETE FROM App\Entity\DataSortInvocateur')->execute();

        // Récupérer les sorts d'invocateur depuis le service
        $sortsInvocateur = $sortInvocateurService->getSortsInvocateurId();

        // Ajouter chaque sort d'invocateur à la base de données
        foreach ($sortsInvocateur as $sortInvocateur) {
            $newSort = new DataSortInvocateur();
            $newSort->setId($sortInvocateur);
            $em->persist($newSort);
        }

        // Flush les changements à la base de données
        $em->flush();

        return new Response('Sorts d\'invocateur importés ou mis à jour avec succès !');
    }

    // Ajouter ou mettre à jour les ID et infos des runes de la table data_runes dans la DB
    #[Route('/admin/data_runes_update', name: 'update_data_runes')]
    public function runesUpdate(RuneService $runeService, EntityManagerInterface $em)
    {
        // Supprimer toutes les entrées existantes de la table des runes
        $em->createQuery('DELETE FROM App\Entity\DataRune')->execute();

        // Supprimer toutes les entrées existantes de la table des bonus
        $em->createQuery('DELETE FROM App\Entity\DataStatistiqueBonus')->execute();

        // Récupération des arbres de runes
        $arbres = $runeService->getRunes();

        foreach ($arbres as $arbreName => $arbre) {
            $rune_arbre = $arbreName;

            foreach ($arbre['slots'] as $slotKey => $slot) {
                $rune_type = $slotKey === 'Primary' ? 'Primary' : 'Secondary' . $slotKey;

                foreach ($slot['runes'] as $rune) {
                    $runeId = $rune['id'];

                    $dataRune = new DataRune();
                    $dataRune->setId($runeId);
                    $dataRune->setRuneArbre($rune_arbre);
                    $dataRune->setRuneType($rune_type);

                    $em->persist($dataRune);
                }
            }
        }

        // Récupération des statistiques bonus
        $bonusStatistiques = $runeService->getStatistiquesBonus();

        // Pour chaque ligne de bonus
        foreach ($bonusStatistiques as $ligne => $bonusValues) {
            foreach ($bonusValues as $id => $bonusValue) {
                $dataBonus = new DataStatistiqueBonus();
                $dataBonus->setId($id);
                $dataBonus->setBonusValue($bonusValue);
                $dataBonus->setBonusLine($ligne);
                $em->persist($dataBonus);
            }
        }

        $em->flush();

        return new Response('Runes et bonus importés avec succès !');
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
