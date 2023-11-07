<?php

namespace App\Service;

use App\Entity\DataCompetence;
use App\Entity\AssociationsRunesBonus;
use App\Entity\AssociationsArbresRunes;
use App\Entity\AssociationsCompetencesNiveaux;
use App\Entity\RunesPage;

class GuideService
{
    public function runesHelper($form, $guide, $groupesRunes, $entityManager)
    {
        // Récupère les RunesPages existants à partir de l'entité Guide
        $existingRunesPages = $guide->getGroupeRunes();

        foreach ($groupesRunes as $key => $groupeRunes) {
            $runesPage = $existingRunesPages->get($key);

            $runesPage->setTitre($groupeRunes->getTitre());
            $runesPage->setCommentaire($groupeRunes->getCommentaire());
            $runesPage->setOrdre($groupeRunes->getOrdre());

            // Récupère les objet data runes
            $arbres = ['Domination', 'Inspiration', 'Precision', 'Resolve', 'Sorcery'];
            $types = ['Primary', 'Secondary1', 'Secondary2', 'Secondary3'];

            foreach ($arbres as $arbre) {
                foreach ($types as $type) {
                    $dataRune = $form->get('groupeRunes')->get($key)->get($arbre)->get($type)->getData();
                    $type = $form->get('groupeRunes')->get($key)->get($arbre)->get('typeArbre')->getData();
                    if ($dataRune) {
                        $associationArbresRunes = new AssociationsArbresRunes();
                        $associationArbresRunes->setType($type);
                        $associationArbresRunes->addChoixRune($dataRune);
                        $entityManager->persist($associationArbresRunes);

                        $associationRunesBonus = new AssociationsRunesBonus();
                        $associationRunesBonus->setType('Rune');
                        $associationRunesBonus->addChoixArbre($associationArbresRunes);
                        $entityManager->persist($associationRunesBonus);

                        $runesPage->addChoixRunesPages($associationRunesBonus);
                    }
                }
            }

            // Récupère les objets de data stat bonus
            $bonusLines = [1, 2, 3];

            foreach ($bonusLines as $line) {
                $dataBonus = $form->get('groupeRunes')->get($key)->get('bonusLine' . $line)->getData();
                if ($dataBonus) {
                    $associationRunesBonus = new AssociationsRunesBonus();
                    $associationRunesBonus->setType('Bonus');
                    $associationRunesBonus->addChoixStatistiquesBonu($dataBonus);
                    $entityManager->persist($associationRunesBonus);

                    $runesPage->addChoixRunesPages($associationRunesBonus);
                }
            }

            $entityManager->persist($runesPage);
            $guide->addGroupeRune($runesPage);
        }
    }

    public function competencesHelper($guide, $groupesCompetences, $groupesCompetencesData, $entityManager)
    {
        // Récupère les RunesPages existants à partir de l'entité Guide
        $existingCompetencesGroupes = $guide->getGroupesCompetences();

        $repository = $entityManager->getRepository(DataCompetence::class);

        foreach ($groupesCompetences as $key => $groupeCompetence) {
            $formCompetences = $existingCompetencesGroupes->get($key);

            $formCompetences->setTitre($groupeCompetence->getTitre());
            $formCompetences->setCommentaire($groupeCompetence->getCommentaire());
            $formCompetences->setOrdre($groupeCompetence->getOrdre());

            $competencesData = $groupesCompetencesData[$key]['competence'];

            foreach ($competencesData as $id => $competenceData) {
                $dataCompetence = $repository->findOneBy(['id' => $id]);

                if ($dataCompetence) {
                    $associationsCompetencesNiveaux = new AssociationsCompetencesNiveaux();
                    $associationsCompetencesNiveaux->setNiveaux($competenceData);
                    $associationsCompetencesNiveaux->addCompetence($dataCompetence);
                    $entityManager->persist($associationsCompetencesNiveaux);

                    $formCompetences->addChoixCompetencesNiveau($associationsCompetencesNiveaux);
                }
            }
            $guide->addGroupesCompetence($formCompetences);
        }
    }
}
