<?php

namespace App\Entity;

use App\Repository\DataCompetenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DataCompetenceRepository::class)]
class DataCompetence
{
    #[ORM\Id]
    #[ORM\Column(length: 30)]
    private ?string $id = null;

    #[ORM\Column(length: 20)]
    private ?string $nomChampion = null;

    #[ORM\Column(length: 10)]
    private ?string $type = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNomChampion(): ?string
    {
        return $this->nomChampion;
    }

    public function setNomChampion(string $nomChampion): static
    {
        $this->nomChampion = $nomChampion;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }
}
