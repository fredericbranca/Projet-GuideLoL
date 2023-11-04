<?php

namespace App\Entity;

use App\Repository\RunesPageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RunesPageRepository::class)]
class RunesPage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column]
    private ?int $ordre = null;

    #[ORM\ManyToOne(inversedBy: 'groupeRunes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Guide $guide = null;

    #[ORM\ManyToMany(targetEntity: AssociationsRunesBonus::class, inversedBy: 'choixRunesPages')]
    #[ORM\JoinTable(name: "choix_runes_pages")]
    private Collection $choixRunesPages;

    public function __construct()
    {
        $this->choixRunesPages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): static
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

    public function getGuide(): ?Guide
    {
        return $this->guide;
    }

    public function setGuide(?Guide $guide): static
    {
        $this->guide = $guide;

        return $this;
    }

    /**
     * @return Collection<int, AssociationsRunesBonus>
     */
    public function getChoixRunesPages(): Collection
    {
        return $this->choixRunesPages;
    }

    public function addChoixRunesPages(AssociationsRunesBonus $choixRunesPages): static
    {
        if (!$this->choixRunesPages->contains($choixRunesPages)) {
            $this->choixRunesPages->add($choixRunesPages);
        }

        return $this;
    }

    public function removeChoixRunesPages(AssociationsRunesBonus $choixRunesPages): static
    {
        $this->choixRunesPages->removeElement($choixRunesPages);

        return $this;
    }
}
