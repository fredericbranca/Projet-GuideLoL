<?php

namespace App\Controller;

use App\Entity\DataItem;
use App\Entity\DataRune;
use App\Entity\DataChampion;
use App\Entity\DataCompetence;
use App\Service\ItemService;
use App\Service\RuneService;
use App\Service\ChampionService;
use App\Entity\DataSortInvocateur;
use App\Entity\DataStatistiqueBonus;
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

                if (strpos($bonusValue, "force adaptative") !== false) {
                    $dataBonus->setIcon("/perk-images/StatMods/StatModsAdaptiveForceIcon.png");
                } elseif (strpos($bonusValue, "vitesse d'attaque") !== false) {
                    $dataBonus->setIcon("/perk-images/StatMods/StatModsAttackSpeedIcon.png");
                } elseif (strpos($bonusValue, "accélération de compétence") !== false) {
                    $dataBonus->setIcon("/perk-images/StatMods/StatModsCDRScalingIcon.png");
                } elseif (strpos($bonusValue, "armure") !== false) {
                    $dataBonus->setIcon("/perk-images/StatMods/StatModsArmorIcon.png");
                } elseif (strpos($bonusValue, "résistance magique") !== false) {
                    $dataBonus->setIcon("/perk-images/StatMods/StatModsMagicResIcon.png");
                } elseif (strpos($bonusValue, "PV") !== false) {
                    $dataBonus->setIcon("/perk-images/StatMods/StatModsHealthScalingIcon.png");
                }

                $em->persist($dataBonus);
            }
        }

        $em->flush();

        return new Response('Runes et bonus importés avec succès !');
    }

    // Ajouter ou mettre à jour l'ID des items de la table data_item dans la DB
    #[Route('/admin/data_item_update', name: 'update_data_item')]
    public function itemUpdate(ItemService $itemService, EntityManagerInterface $em)
    {
        // Supprimer toutes les entrées existantes de la table des items
        $em->createQuery('DELETE FROM App\Entity\DataItem')->execute();

        // Récupérer les items depuis le service
        $items = $itemService->getItems();

        // Ajouter chaque champion à la base de données
        foreach ($items as $id => $item) {
            $itemId = new DataItem;
            $itemId->setId($id);
            $em->persist($itemId);
        }

        // Flush les changements à la base de données
        $em->flush();

        return new Response('Items importés ou mis à jour avec succès !');
    }

    // Ajouter ou mettre à jour l'ID des compétences de la table data_competence dans la DB
    #[Route('/admin/data_competence_update', name: 'update_data_competence')]
    public function competenceUpdate(ChampionService $championService, EntityManagerInterface $em)
    {
        // Supprimer toutes les entrées existantes de la table des items
        $em->createQuery('DELETE FROM App\Entity\DataCompetence')->execute();

        // Récupérer les items depuis le service
        $champions = $championService->getChampions();
        $types = ['A', 'Z', 'E', 'R'];
        // Ajouter chaque compétence à la base de données
        foreach ($champions as $champion) {
            foreach ($champion['spells'] as $key => $spell) {
                $competence = new DataCompetence();
                $competence->setId($spell['id']);
                $competence->setNomChampion($champion['idChamp']);
                $competence->setType($types[$key] ?? null);
                $em->persist($competence);
            }
        }

        // Flush les changements à la base de données
        $em->flush();

        return new Response('Compétences importés ou mis à jour avec succès !');
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
