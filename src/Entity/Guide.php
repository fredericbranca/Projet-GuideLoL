<?php

namespace App\Entity;

use App\Repository\GuideRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GuideRepository::class)]
class Guide
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $champion = null;

    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\Column(length: 10)]
    private ?string $voie = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $modified_at = null;

    #[ORM\OneToMany(mappedBy: 'guide', targetEntity: SortInvocateur::class, orphanRemoval: true)]
    private Collection $groupeSortsInvocateur;

    #[ORM\OneToMany(mappedBy: 'guide', targetEntity: RunesPage::class, orphanRemoval: true)]
    private Collection $groupeRunes;

    #[ORM\OneToMany(mappedBy: 'guide', targetEntity: EnsembleItemsGroups::class)]
    private Collection $GroupeEnsemblesItems;

    public function __construct()
    {
        $this->groupeSortsInvocateur = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable(); // Permet de mettre la date de creation à created_at lors de la création de l'objet
        $this->groupeRunes = new ArrayCollection();
        $this->GroupeEnsemblesItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChampion(): ?string
    {
        return $this->champion;
    }

    public function setChampion(?string $champion): static
    {
        $this->champion = $champion;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getVoie(): ?string
    {
        return $this->voie;
    }

    public function setVoie(string $voie): static
    {
        $this->voie = $voie;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeImmutable
    {
        return $this->modified_at;
    }

    public function setModifiedAt(?\DateTimeImmutable $modified_at): static
    {
        $this->modified_at = $modified_at;

        return $this;
    }

    /**
     * @return Collection<int, SortInvocateur>
     */
    public function getGroupeSortsInvocateur(): Collection
    {
        return $this->groupeSortsInvocateur;
    }

    public function addGroupeSortsInvocateur(SortInvocateur $groupeSortsInvocateur): static
    {
        if (!$this->groupeSortsInvocateur->contains($groupeSortsInvocateur)) {
            $this->groupeSortsInvocateur->add($groupeSortsInvocateur);
            $groupeSortsInvocateur->setGuide($this);
        }

        return $this;
    }

    public function removeGroupeSortsInvocateur(SortInvocateur $groupeSortsInvocateur): static
    {
        if ($this->groupeSortsInvocateur->removeElement($groupeSortsInvocateur)) {
            // set the owning side to null (unless already changed)
            if ($groupeSortsInvocateur->getGuide() === $this) {
                $groupeSortsInvocateur->setGuide(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RunesPage>
     */
    public function getGroupeRunes(): Collection
    {
        return $this->groupeRunes;
    }

    public function addGroupeRune(RunesPage $groupeRune): static
    {
        if (!$this->groupeRunes->contains($groupeRune)) {
            $this->groupeRunes->add($groupeRune);
            $groupeRune->setGuide($this);
        }

        return $this;
    }

    public function removeGroupeRune(RunesPage $groupeRune): static
    {
        if ($this->groupeRunes->removeElement($groupeRune)) {
            // set the owning side to null (unless already changed)
            if ($groupeRune->getGuide() === $this) {
                $groupeRune->setGuide(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EnsembleItemsGroups>
     */
    public function getGroupeEnsemblesItems(): Collection
    {
        return $this->GroupeEnsemblesItems;
    }

    public function addGroupeEnsemblesItem(EnsembleItemsGroups $groupeEnsemblesItem): static
    {
        if (!$this->GroupeEnsemblesItems->contains($groupeEnsemblesItem)) {
            $this->GroupeEnsemblesItems->add($groupeEnsemblesItem);
            $groupeEnsemblesItem->setGuide($this);
        }

        return $this;
    }

    public function removeGroupeEnsemblesItem(EnsembleItemsGroups $groupeEnsemblesItem): static
    {
        if ($this->GroupeEnsemblesItems->removeElement($groupeEnsemblesItem)) {
            // set the owning side to null (unless already changed)
            if ($groupeEnsemblesItem->getGuide() === $this) {
                $groupeEnsemblesItem->setGuide(null);
            }
        }

        return $this;
    }
}
