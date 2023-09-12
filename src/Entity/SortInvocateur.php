<?php

namespace App\Entity;

use App\Repository\SortInvocateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SortInvocateurRepository::class)]
class SortInvocateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column]
    private ?int $ordre = null;

    #[ORM\ManyToMany(targetEntity: DataSortInvocateur::class)]
    private Collection $ChoixSortInvocateur;

    #[ORM\ManyToOne(inversedBy: 'GroupeSortInvocateur')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Guide $guide = null;

    public function __construct()
    {
        $this->ChoixSortInvocateur = new ArrayCollection();
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

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): static
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * @return Collection<int, DataSortInvocateur>
     */
    public function getChoixSortInvocateur(): Collection
    {
        return $this->ChoixSortInvocateur;
    }

    public function addChoixSortInvocateur(DataSortInvocateur $choixSortInvocateur): static
    {
        if (!$this->ChoixSortInvocateur->contains($choixSortInvocateur)) {
            $this->ChoixSortInvocateur->add($choixSortInvocateur);
        }

        return $this;
    }

    public function removeChoixSortInvocateur(DataSortInvocateur $choixSortInvocateur): static
    {
        $this->ChoixSortInvocateur->removeElement($choixSortInvocateur);

        return $this;
    }

    public function getGuide(): ?Guide
    {
        return $this->guide;
    }

    public function setGuide(?Guide $guide): static
    {
        $this->guide = $guide;

        return $this;
    }
}
