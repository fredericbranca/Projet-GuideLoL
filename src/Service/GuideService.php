<?php

namespace App\Service;

use App\Entity\Guide;
use App\Entity\SortInvocateur;
use Doctrine\ORM\EntityManagerInterface;

class GuideService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createGuideFromForm($formData, $champion, $groupesSortsInvocateur)
    {
        // Première partie du guide
        $guide = new Guide();
        $guide->setTitre($formData->getTitre());
        $guide->setVoie($formData->getVoie());
        $guide->setChampion($champion);
        // Persister et flusher le guide pour obtenir son ID
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
        $this->entityManager->flush();
    }
}
