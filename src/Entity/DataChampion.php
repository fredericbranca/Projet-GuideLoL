<?php

namespace App\Entity;

use App\Repository\DataChampionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DataChampionRepository::class)]
class DataChampion
{
    #[ORM\Id]
    #[ORM\Column(length: 20)]
    private ?string $id = null;

    #[ORM\OneToMany(mappedBy: 'champion', targetEntity: Guide::class)]
    private Collection $guides;

    public function __construct()
    {
        $this->guides = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Collection<int, Guide>
     */
    public function getGuides(): Collection
    {
        return $this->guides;
    }

    // Fonction qui retourne l'id de l'objet
    function __toString()
    {
        return $this->getId();
    }
}
