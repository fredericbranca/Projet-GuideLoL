<?php

namespace App\Entity;

use App\Repository\EnsembleItemsGroupsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnsembleItemsGroupsRepository::class)]
class EnsembleItemsGroups
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $titre = null;

    #[ORM\Column]
    private ?int $ordre = null;

    #[ORM\OneToMany(mappedBy: 'ensembleItemsGroups', targetEntity: ItemsGroup::class, cascade: ['persist'])]
    #[ORM\OrderBy(["ordre" => "ASC"])]
    private Collection $AssociationsEnsemblesItemsGroups;

    #[ORM\ManyToOne(inversedBy: 'GroupeEnsemblesItems')]
    private ?Guide $guide = null;

    public function __construct()
    {
        $this->AssociationsEnsemblesItemsGroups = new ArrayCollection();
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
     * @return Collection<int, ItemsGroup>
     */
    public function getAssociationsEnsemblesItemsGroups(): Collection
    {
        return $this->AssociationsEnsemblesItemsGroups;
    }

    public function addAssociationsEnsemblesItemsGroup(ItemsGroup $associationsEnsemblesItemsGroup): static
    {
        if (!$this->AssociationsEnsemblesItemsGroups->contains($associationsEnsemblesItemsGroup)) {
            $this->AssociationsEnsemblesItemsGroups->add($associationsEnsemblesItemsGroup);
            $associationsEnsemblesItemsGroup->setEnsembleItemsGroups($this);
        }

        return $this;
    }

    public function removeAssociationsEnsemblesItemsGroup(ItemsGroup $associationsEnsemblesItemsGroup): static
    {
        if ($this->AssociationsEnsemblesItemsGroups->removeElement($associationsEnsemblesItemsGroup)) {
            // set the owning side to null (unless already changed)
            if ($associationsEnsemblesItemsGroup->getEnsembleItemsGroups() === $this) {
                $associationsEnsemblesItemsGroup->setEnsembleItemsGroups(null);
            }
        }

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
