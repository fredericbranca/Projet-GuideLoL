<?php

namespace App\Service;

use App\Entity\Guide;
use App\Entity\RunesPage;
use App\Entity\SortInvocateur;
use App\Entity\AssociationsRunesBonus;
use App\Entity\AssociationsArbresRunes;
use Doctrine\ORM\EntityManagerInterface;

class GuideService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createGuideFromForm($formData, $champion, $groupesSortsInvocateur, $groupesRunes, $runesData)
    {
        // Première partie du guide
        $guide = new Guide();
        $guide->setTitre($formData->getTitre());
        $guide->setVoie($formData->getVoie());
        $guide->setChampion($champion);
        // Persist et flush le guide pour obtenir son ID
        $this->entityManager->persist($guide);
        $this->entityManager->flush();

        // Sorts d'invocateur
        foreach ($groupesSortsInvocateur as $groupeSortsInvocateur) {
            $sortInvocateur = new SortInvocateur;
            $sortInvocateur->setGuide($guide);
            $sortInvocateur->setTitre($groupeSortsInvocateur->getTitre());
            $sortInvocateur->setCommentaire($groupeSortsInvocateur->getCommentaire());
            $sortInvocateur->setOrdre($groupeSortsInvocateur->getOrdre());
            $spells = $groupeSortsInvocateur->getChoixSortInvocateur();
            foreach ($spells as $spell) {
                $sortInvocateur->addChoixSortInvocateur($spell);
            }
            $this->entityManager->persist($sortInvocateur);
        }

        // Runes
        foreach ($groupesRunes as $groupeRunes) {
            $runesPage = new RunesPage();
            $runesPage->setGuide($guide);
            $runesPage->setTitre($groupeRunes->getTitre());
            $runesPage->setCommentaire($groupeRunes->getCommentaire());
            $runesPage->setOrdre($groupeRunes->getOrdre());

            // Persiste et flush le groupe de runes pour obtenir son ID
            $this->entityManager->persist($runesPage);
            $this->entityManager->flush();

            foreach ($runesData as $rune) {
                $associationArbresRunes = new AssociationsArbresRunes();
                $associationArbresRunes->setType('test');
                $associationArbresRunes->addChoixRune($rune);
                $this->entityManager->persist($associationArbresRunes);
                $this->entityManager->flush();

                $associationRunesBonus = new AssociationsRunesBonus();
                $associationRunesBonus->setType('Rune');
                $associationRunesBonus->addChoixArbre($associationArbresRunes);
                $this->entityManager->persist($associationRunesBonus);
                $this->entityManager->flush();

                $runesPage->addChoixRunesPages($associationRunesBonus);
            }
            $this->entityManager->persist($runesPage);
        }

        $this->entityManager->flush();
    }
}
