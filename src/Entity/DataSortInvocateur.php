<?php

namespace App\Entity;

use App\Repository\DataSortInvocateurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DataSortInvocateurRepository::class)]
class DataSortInvocateur
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
