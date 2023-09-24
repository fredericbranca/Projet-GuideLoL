<?php

namespace App\Entity;

use App\Repository\DataSortInvocateurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DataSortInvocateurRepository::class)]
class DataSortInvocateur
{
    #[ORM\Id]
    #[ORM\Column(length: 30)]
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

    public function __toString(): string
    {
        return $this->id;
    }
}
