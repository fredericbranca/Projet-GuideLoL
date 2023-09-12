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

    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\Column(length: 10)]
    private ?string $voie = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $modified_at = null;

    #[ORM\OneToMany(mappedBy: 'guide', targetEntity: SortInvocateur::class, orphanRemoval: true)]
    private Collection $GroupeSortInvocateur;

    #[ORM\ManyToOne(inversedBy: 'guides')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DataChampion $champion = null;

    public function __construct()
    {
        $this->GroupeSortInvocateur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
    public function getGroupeSortInvocateur(): Collection
    {
        return $this->GroupeSortInvocateur;
    }

    public function addGroupeSortInvocateur(SortInvocateur $groupeSortInvocateur): static
    {
        if (!$this->GroupeSortInvocateur->contains($groupeSortInvocateur)) {
            $this->GroupeSortInvocateur->add($groupeSortInvocateur);
            $groupeSortInvocateur->setGuide($this);
        }

        return $this;
    }

    public function removeGroupeSortInvocateur(SortInvocateur $groupeSortInvocateur): static
    {
        if ($this->GroupeSortInvocateur->removeElement($groupeSortInvocateur)) {
            // set the owning side to null (unless already changed)
            if ($groupeSortInvocateur->getGuide() === $this) {
                $groupeSortInvocateur->setGuide(null);
            }
        }

        return $this;
    }

    public function getChampion(): ?DataChampion
    {
        return $this->champion;
    }

    public function setChampion(?DataChampion $champion): static
    {
        $this->champion = $champion;

        return $this;
    }
}
