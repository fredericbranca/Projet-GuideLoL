<?php

namespace App\Entity;

use App\Repository\AssociationsRunesBonusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssociationsRunesBonusRepository::class)]
class AssociationsRunesBonus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $type = null;

    #[ORM\ManyToMany(targetEntity: RunesPage::class, mappedBy: 'choixRunesPages', cascade: ['persist'])]
    private Collection $runesPages;

    #[ORM\ManyToMany(targetEntity: DataStatistiqueBonus::class)]
    #[ORM\JoinTable(name: "choix_statistiques_bonus")]
    private Collection $choixStatistiquesBonus;

    #[ORM\ManyToMany(targetEntity: AssociationsArbresRunes::class, inversedBy: 'associationsRunesBonuses', orphanRemoval: true, cascade: ['persist', 'remove'])]
    #[ORM\JoinTable(name: "choix_arbres")]
    private Collection $choixArbres;

    public function __construct()
    {
        $this->runesPages = new ArrayCollection();
        $this->choixStatistiquesBonus = new ArrayCollection();
        $this->choixArbres = new ArrayCollection();
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
     * @return Collection<int, RunesPage>
     */
    public function getRunesPages(): Collection
    {
        return $this->runesPages;
    }

    public function addRunesPage(RunesPage $runesPage): static
    {
        if (!$this->runesPages->contains($runesPage)) {
            $this->runesPages->add($runesPage);
            $runesPage->addChoixRunesPages($this);
        }

        return $this;
    }

    public function removeRunesPage(RunesPage $runesPage): static
    {
        if ($this->runesPages->removeElement($runesPage)) {
            $runesPage->removeChoixRunesPages($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, DataStatistiqueBonus>
     */
    public function getChoixStatistiquesBonus(): Collection
    {
        return $this->choixStatistiquesBonus;
    }

    public function addChoixStatistiquesBonu(DataStatistiqueBonus $choixStatistiquesBonu): static
    {
        if (!$this->choixStatistiquesBonus->contains($choixStatistiquesBonu)) {
            $this->choixStatistiquesBonus->add($choixStatistiquesBonu);
        }

        return $this;
    }

    public function removeChoixStatistiquesBonu(DataStatistiqueBonus $choixStatistiquesBonu): static
    {
        $this->choixStatistiquesBonus->removeElement($choixStatistiquesBonu);

        return $this;
    }

    /**
     * @return Collection<int, AssociationsArbresRunes>
     */
    public function getChoixArbres(): Collection
    {
        return $this->choixArbres;
    }

    public function addChoixArbre(AssociationsArbresRunes $choixArbre): static
    {
        if (!$this->choixArbres->contains($choixArbre)) {
            $this->choixArbres->add($choixArbre);
        }

        return $this;
    }

    public function removeChoixArbre(AssociationsArbresRunes $choixArbre): static
    {
        $this->choixArbres->removeElement($choixArbre);

        return $this;
    }

    public function __toString()
    {
        return $this->id;
    }
}
