<?php

namespace App\Entity;

use App\Repository\ItemsGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemsGroupRepository::class)]
class ItemsGroup
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

    #[ORM\ManyToOne(inversedBy: 'AssociationsEnsemblesItemsGroups')]
    private ?EnsembleItemsGroups $ensembleItemsGroups = null;

    #[ORM\ManyToMany(targetEntity: OrdreItems::class, inversedBy: 'itemsGroups')]
    #[ORM\JoinTable(name: "choix_items_ordonnees")]
    private Collection $choixItemsOrdonnees;

    public function __construct()
    {
        $this->choixItemsOrdonnees = new ArrayCollection();
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

    public function getEnsembleItemsGroups(): ?EnsembleItemsGroups
    {
        return $this->ensembleItemsGroups;
    }

    public function setEnsembleItemsGroups(?EnsembleItemsGroups $ensembleItemsGroups): static
    {
        $this->ensembleItemsGroups = $ensembleItemsGroups;

        return $this;
    }

    /**
     * @return Collection<int, OrdreItems>
     */
    public function getChoixItemsOrdonnees(): Collection
    {
        return $this->choixItemsOrdonnees;
    }

    public function addChoixItemsOrdonnee(OrdreItems $choixItemsOrdonnee): static
    {
        if (!$this->choixItemsOrdonnees->contains($choixItemsOrdonnee)) {
            $this->choixItemsOrdonnees->add($choixItemsOrdonnee);
        }

        return $this;
    }

    public function removeChoixItemsOrdonnee(OrdreItems $choixItemsOrdonnee): static
    {
        $this->choixItemsOrdonnees->removeElement($choixItemsOrdonnee);

        return $this;
    }
}
