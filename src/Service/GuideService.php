<?php

namespace App\Service;

use App\Entity\AssociationsRunesBonus;
use App\Entity\AssociationsArbresRunes;

class GuideService
{
    public function runesHelper($form, $guide, $runesPage, $groupesRunes, $entityManager)
    {
        foreach ($groupesRunes as $key => $groupeRunes) {
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
                $dataBonus = $form->get('groupeRunes')->get($key)->get('bonusLine'.$line)->getData();
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
}
