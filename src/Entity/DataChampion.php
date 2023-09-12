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
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'champion', targetEntity: Guide::class)]
    private Collection $guides;

    public function __construct()
    {
        $this->guides = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Guide>
     */
    public function getGuides(): Collection
    {
        return $this->guides;
    }
}
