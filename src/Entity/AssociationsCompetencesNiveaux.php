<?php

namespace App\Entity;

use App\Repository\AssociationsCompetencesNiveauxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssociationsCompetencesNiveauxRepository::class)]
class AssociationsCompetencesNiveaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $niveaux = [];

    #[ORM\ManyToMany(targetEntity: CompetencesGroup::class, mappedBy: 'choixCompetencesNiveaux')]
    private Collection $competencesGroups;

    #[ORM\ManyToMany(targetEntity: DataCompetence::class)]
    #[ORM\JoinTable(name: "competences")]
    private Collection $competences;

    public function __construct()
    {
        $this->competencesGroups = new ArrayCollection();
        $this->competences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNiveaux(): array
    {
        return $this->niveaux;
    }

    public function setNiveaux(array $niveaux): static
    {
        $this->niveaux = $niveaux;

        return $this;
    }

    /**
     * @return Collection<int, CompetencesGroup>
     */
    public function getCompetencesGroups(): Collection
    {
        return $this->competencesGroups;
    }

    public function addCompetencesGroup(CompetencesGroup $competencesGroup): static
    {
        if (!$this->competencesGroups->contains($competencesGroup)) {
            $this->competencesGroups->add($competencesGroup);
            $competencesGroup->addChoixCompetencesNiveau($this);
        }

        return $this;
    }

    public function removeCompetencesGroup(CompetencesGroup $competencesGroup): static
    {
        if ($this->competencesGroups->removeElement($competencesGroup)) {
            $competencesGroup->removeChoixCompetencesNiveau($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, DataCompetence>
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(DataCompetence $competence): static
    {
        if (!$this->competences->contains($competence)) {
            $this->competences->add($competence);
        }

        return $this;
    }

    public function removeCompetence(DataCompetence $competence): static
    {
        $this->competences->removeElement($competence);

        return $this;
    }
}
