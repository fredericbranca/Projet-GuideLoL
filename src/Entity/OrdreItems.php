<?php

namespace App\Entity;

use App\Repository\OrdreItemsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdreItemsRepository::class)]
class OrdreItems
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $ordre = null;

    #[ORM\ManyToMany(targetEntity: ItemsGroup::class, mappedBy: 'choixItemsOrdonnees')]
    private Collection $itemsGroups;

    #[ORM\ManyToMany(targetEntity: DataItem::class)]
    #[ORM\JoinTable(name: "choix_items")]
    private Collection $choixItems;

    public function __construct()
    {
        $this->itemsGroups = new ArrayCollection();
        $this->choixItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
    public function getItemsGroups(): Collection
    {
        return $this->itemsGroups;
    }

    public function addItemsGroup(ItemsGroup $itemsGroup): static
    {
        if (!$this->itemsGroups->contains($itemsGroup)) {
            $this->itemsGroups->add($itemsGroup);
            $itemsGroup->addChoixItemsOrdonnee($this);
        }

        return $this;
    }

    public function removeItemsGroup(ItemsGroup $itemsGroup): static
    {
        if ($this->itemsGroups->removeElement($itemsGroup)) {
            $itemsGroup->removeChoixItemsOrdonnee($this);
        }

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
}
