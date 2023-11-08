<?php

namespace App\Entity;

use App\Repository\DataStatistiqueBonusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DataStatistiqueBonusRepository::class)]
class DataStatistiqueBonus
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $bonus_value = null;

    #[ORM\Column]
    private ?int $bonus_line = null;

    #[ORM\Column(length: 255)]
    private ?string $icon = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getBonusValue(): ?string
    {
        return $this->bonus_value;
    }

    public function setBonusValue(string $bonus_value): static
    {
        $this->bonus_value = $bonus_value;

        return $this;
    }

    public function getBonusLine(): ?int
    {
        return $this->bonus_line;
    }

    public function setBonusLine(int $bonus_line): static
    {
        $this->bonus_line = $bonus_line;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }
}
