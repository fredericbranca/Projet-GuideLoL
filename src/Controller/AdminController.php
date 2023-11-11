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
        // Récupérer les champions depuis le service
        $champions = $championService->getChampionsName();

        foreach ($champions as $championName) {
            // Vérifier si le champion existe déjà
            $champion = $em->getRepository(DataChampion::class)->find($championName);

            // Si le champion n'existe pas, on le crée
            if (!$champion) {
                $champion = new DataChampion();
                $champion->setId($championName);
            }

            // Persist l'entité champion, que ce soit une nouvelle entrée ou une mise à jour
            $em->persist($champion);
        }

        // Flush les changements à la base de données
        $em->flush();

        return new Response('Nom des champions importés ou mis à jour avec succès !');
    }

    // Ajouter ou mettre à jour les ID des sorts d'invocateur de la table data_sort_invocateur dans la DB
    #[Route('/admin/data_sort_invocateur_update', name: 'update_data_sort_invocateur')]
    public function sortsInvocateurUpdate(SortInvocateurService $sortInvocateurService, EntityManagerInterface $em)
    {
        // Récupérer les sorts d'invocateur depuis le service
        $sortsInvocateur = $sortInvocateurService->getSortsInvocateurId();

        foreach ($sortsInvocateur as $sortInvocateurId) {
            // Vérifier si le sort d'invocateur existe déjà
            $sortInvocateur = $em->getRepository(DataSortInvocateur::class)->find($sortInvocateurId);

            // Si le sort d'invocateur n'existe pas, on le crée
            if (!$sortInvocateur) {
                $sortInvocateur = new DataSortInvocateur();
                $sortInvocateur->setId($sortInvocateurId);
            }

            // Persist l'entité sort d'invocateur, que ce soit une nouvelle entrée ou une mise à jour
            $em->persist($sortInvocateur);
        }

        // Flush les changements à la base de données
        $em->flush();

        return new Response('Sorts d\'invocateur importés ou mis à jour avec succès !');
    }

    // Ajouter ou mettre à jour les ID et infos des runes de la table data_runes dans la DB
    #[Route('/admin/data_runes_update', name: 'update_data_runes')]
    public function runesUpdate(RuneService $runeService, EntityManagerInterface $em)
    {
        // Récupération des arbres de runes
        $arbres = $runeService->getRunes();

        foreach ($arbres as $arbreName => $arbre) {
            $rune_arbre = $arbreName;

            foreach ($arbre['slots'] as $slotKey => $slot) {
                $rune_type = $slotKey === 'Primary' ? 'Primary' : 'Secondary' . $slotKey;

                foreach ($slot['runes'] as $key => $rune) {
                    $runeId = $rune['id'];

                    // Vérifier si la rune existe déjà
                    $dataRune = $em->getRepository(DataRune::class)->find($runeId);

                    // Si la rune n'existe pas, on la crée
                    if (!$dataRune) {
                        $dataRune = new DataRune();
                        $dataRune->setId($runeId);
                    }

                    // Mise à jour ou ajout des informations de la rune
                    $dataRune->setRuneArbre($rune_arbre);
                    $dataRune->setRuneType($rune_type);
                    $dataRune->setOrdre($key);

                    // Persist l'entité rune
                    $em->persist($dataRune);
                }
            }
        }

        // Récupération des statistiques bonus
        $bonusStatistiques = $runeService->getStatistiquesBonus();

        foreach ($bonusStatistiques as $ligne => $bonusValues) {
            foreach ($bonusValues as $id => $bonusValue) {
                // Vérifier si le bonus existe déjà
                $dataBonus = $em->getRepository(DataStatistiqueBonus::class)->find($id);

                // Si le bonus n'existe pas, on le crée
                if (!$dataBonus) {
                    $dataBonus = new DataStatistiqueBonus();
                    $dataBonus->setId($id);
                }

                // Mise à jour ou ajout des informations du bonus
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

        // Flush les changements à la base de données
        $em->flush();

        return new Response('Runes et bonus importés ou mis à jour avec succès !');
    }

    // Ajouter ou mettre à jour l'ID des items de la table data_item dans la DB
    #[Route('/admin/data_item_update', name: 'update_data_item')]
    public function itemUpdate(ItemService $itemService, EntityManagerInterface $em)
    {
        // Récupérer les items depuis le service
        $items = $itemService->getItems();

        // Vérifier et mettre à jour ou ajouter chaque item dans la base de données
        foreach ($items as $id => $itemData) {
            // Vérifier si l'item existe déjà dans la base de données
            $itemEntity = $em->getRepository(DataItem::class)->find($id);

            // Si l'item n'existe pas, créer une nouvelle entité
            if (!$itemEntity) {
                $itemEntity = new DataItem();
                $itemEntity->setId($id);
            }

            // Persister l'entité
            $em->persist($itemEntity);
        }

        // Flush les changements à la base de données
        $em->flush();

        return new Response('Items importés ou mis à jour avec succès !');
    }

    // Ajouter ou mettre à jour l'ID des compétences de la table data_competence dans la DB
    #[Route('/admin/data_competence_update', name: 'update_data_competence')]
    public function competenceUpdate(ChampionService $championService, EntityManagerInterface $em)
    {
        // Récupérer les champions et leurs compétences depuis le service
        $champions = $championService->getChampions();
        $types = ['A', 'Z', 'E', 'R'];

        // Parcourir chaque champion et ses compétences
        foreach ($champions as $champion) {
            foreach ($champion['spells'] as $key => $spell) {
                // Vérifier si la compétence existe déjà
                $competence = $em->getRepository(DataCompetence::class)->find($spell['id']);

                // Si la compétence n'existe pas, en créer une nouvelle
                if (!$competence) {
                    $competence = new DataCompetence();
                    $competence->setId($spell['id']);
                }

                // Mettre à jour les informations de la compétence
                $competence->setNomChampion($champion['idChamp']);
                $competence->setType($types[$key] ?? null);
                // Mettre à jour d'autres propriétés de la compétence si nécessaire...

                // Persister l'entité compétence
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
