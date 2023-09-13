<?php

namespace App\DataFixtures;

use App\Entity\DataChampion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $champions = json_decode(file_get_contents(__DIR__ . "/../DataFixtures/Champion/champions.json"), true);

        foreach ($champions as $champion) {

            $dataChampion = new DataChampion();
            $dataChampion->setId($champion['id']);
            $manager->persist($dataChampion);
        }

        $manager->flush();
    }
}
