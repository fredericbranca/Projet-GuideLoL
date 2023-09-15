<?php

namespace App\Entity;

use App\Repository\DataChampionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DataChampionRepository::class)]
class DataChampion
{
    #[ORM\Id]
    #[ORM\Column(length: 20)]
    private ?string $id = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    // Fonction qui retourne l'id de l'objet
    function __toString()
    {
        return $this->getId();
    }
}
