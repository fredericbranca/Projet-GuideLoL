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

    #[ORM\ManyToOne(inversedBy: 'AssociationsEnsemblesItemsGroups', cascade: ['persist'])]
    private ?EnsembleItemsGroups $ensembleItemsGroups = null;

    #[ORM\ManyToMany(targetEntity: DataItem::class)]
    #[ORM\JoinTable(name: "choix_items")]
    private Collection $choixItems;

    #[ORM\Column]
    private array $ordreItems = [];

    public function __construct()
    {
        $this->choixItems = new ArrayCollection();
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
     * @return Collection<int, DataItem>
     */
    public function getChoixItems(): Collection
    {
        return $this->choixItems;
    }

    public function addChoixItem(DataItem $choixItem): static
    {
        if (!$this->choixItems->contains($choixItem)) {
            $this->choixItems->add($choixItem);
        }

        return $this;
    }

    public function removeChoixItem(DataItem $choixItem): static
    {
        $this->choixItems->removeElement($choixItem);

        return $this;
    }

    public function getOrdreItems(): array
    {
        return $this->ordreItems;
    }

    public function setOrdreItems(array $ordreItems): static
    {
        $this->ordreItems = $ordreItems;

        return $this;
    }
}
