<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\AssociationsRunesBonus;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\AssociationsArbresRunesRepository;

#[ORM\Entity(repositoryClass: AssociationsArbresRunesRepository::class)]
class AssociationsArbresRunes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $type = null;

    #[ORM\ManyToMany(targetEntity: DataRune::class)]
    #[ORM\JoinTable(name: "choix_runes")]
    private Collection $choixRunes;

    #[ORM\ManyToMany(targetEntity: AssociationsRunesBonus::class, mappedBy: 'choixArbres', orphanRemoval: true, cascade: ['remove'])]
    private Collection $associationsRunesBonuses;

    public function __construct()
    {
        $this->choixRunes = new ArrayCollection();
        $this->associationsRunesBonuses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, DataRune>
     */
    public function getChoixRunes(): Collection
    {
        return $this->choixRunes;
    }

    public function addChoixRune(DataRune $choixRune): static
    {
        if (!$this->choixRunes->contains($choixRune)) {
            $this->choixRunes->add($choixRune);
        }

        return $this;
    }

    public function removeChoixRune(DataRune $choixRune): static
    {
        $this->choixRunes->removeElement($choixRune);

        return $this;
    }

    public function addAssociationsRunesBonus(AssociationsRunesBonus $associationsRunesBonus): self
    {
        if (!$this->associationsRunesBonuses->contains($associationsRunesBonus)) {
            $this->associationsRunesBonuses->add($associationsRunesBonus);
            $associationsRunesBonus->addChoixArbre($this); // Ceci synchronise le côté inverse de la relation
        }

        return $this;
    }

    public function removeAssociationsRunesBonus(AssociationsRunesBonus $associationsRunesBonus): self
    {
        if ($this->associationsRunesBonuses->removeElement($associationsRunesBonus)) {
            $associationsRunesBonus->removeChoixArbre($this); // Ceci synchronise le côté inverse de la relation
        }

        return $this;
    }

    public function __toString()
    {
        return $this->id;
    }
}
