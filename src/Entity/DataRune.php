<?php

namespace App\Entity;

use App\Repository\DataRuneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DataRuneRepository::class)]
class DataRune
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $runeArbre = null;

    #[ORM\Column(length: 20)]
    private ?string $runeType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getRuneArbre(): ?string
    {
        return $this->runeArbre;
    }

    public function setRuneArbre(string $runeArbre): static
    {
        $this->runeArbre = $runeArbre;

        return $this;
    }

    public function getRuneType(): ?string
    {
        return $this->runeType;
    }

    public function setRuneType(string $runeType): static
    {
        $this->runeType = $runeType;

        return $this;
    }

    public function __toString()
    {
        return $this->id;
    }
}
