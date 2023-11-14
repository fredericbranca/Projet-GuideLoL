<?php

namespace App\Entity;

use App\Repository\CompetencesGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompetencesGroupRepository::class)]
class CompetencesGroup
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

    #[ORM\ManyToOne(inversedBy: 'groupesCompetences')]
    private ?Guide $guide = null;

    #[ORM\ManyToMany(targetEntity: AssociationsCompetencesNiveaux::class, inversedBy: 'choixCompetencesNiveaux', cascade: ['persist', 'remove'])]
    #[ORM\JoinTable(name: "choix_competences_niveaux")]
    private Collection $choixCompetencesNiveaux;

    public function __construct()
    {
        $this->choixCompetencesNiveaux = new ArrayCollection();
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
     * @return Collection<int, AssociationsCompetencesNiveaux>
     */
    public function getChoixCompetencesNiveaux(): Collection
    {
        return $this->choixCompetencesNiveaux;
    }

    public function addChoixCompetencesNiveau(AssociationsCompetencesNiveaux $choixCompetencesNiveau): static
    {
        if (!$this->choixCompetencesNiveaux->contains($choixCompetencesNiveau)) {
            $this->choixCompetencesNiveaux->add($choixCompetencesNiveau);
        }

        return $this;
    }

    public function removeChoixCompetencesNiveau(AssociationsCompetencesNiveaux $choixCompetencesNiveau): static
    {
        $this->choixCompetencesNiveaux->removeElement($choixCompetencesNiveau);

        return $this;
    }
}
