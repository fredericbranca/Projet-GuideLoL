<?php

namespace App\Service;

use App\Entity\Guide;
use Doctrine\ORM\EntityManagerInterface;

class GuideService {
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function createGuideFromForm($formData, $champion) {
        $guide = new Guide();
        $guide = $formData;
        $guide->setChampion($champion);

        $this->entityManager->persist($guide);
        $this->entityManager->flush();
    }
}