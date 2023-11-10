<?php

namespace App\Service;


class CompetenceService
{
    public function getNiveauxParSorts($guide)
    {

        $groupesCompetences = $guide->getGroupesCompetences();

        // Créer un tableau pour stocker les informations combinées pour tous les groupes de compétences
        $informationsTousGroupes = [];

        // Parcourir chaque groupe de compétences
        foreach ($groupesCompetences as $groupe) {
            $choixCompetencesNiveaux = $groupe->getChoixCompetencesNiveaux();

            // Créer un tableau pour le groupe de compétences courant
            $informationsGroupe = [];

            foreach ($choixCompetencesNiveaux as $association) {
                $niveaux = $association->getNiveaux();
                $competences = $association->getCompetences();

                foreach ($competences as $competence) {
                    // Pour chaque compétence, ajouter les détails et les niveaux correspondants dans le tableau
                    $informationsGroupe[] = [
                        'id' => $competence->getId(),
                        'nomChampion' => $competence->getNomChampion(),
                        'type' => $competence->getType(),
                        'niveaux' => $niveaux
                    ];
                }
            }

            // Ajouter les informations du groupe actuel au tableau principal
            $informationsTousGroupes[] = $informationsGroupe;
        }

        return $informationsTousGroupes;
    }
}
