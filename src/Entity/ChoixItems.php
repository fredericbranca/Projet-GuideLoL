<?php

namespace App\Entity;

use App\Repository\ChoixItemsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChoixItemsRepository::class)]
class ChoixItems
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'choixItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ItemsGroup $itemsGroup = null;

    #[ORM\ManyToMany(targetEntity: DataItem::class)]
    private Collection $dataItem;

    #[ORM\Column]
    private ?int $ordre = null;

    public function __construct()
    {
        $this->dataItem = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItemsGroup(): ?ItemsGroup
    {
        return $this->itemsGroup;
    }

    public function setItemsGroup(?ItemsGroup $itemsGroup): static
    {
        $this->itemsGroup = $itemsGroup;

        return $this;
    }

    /**
     * @return Collection<int, DataItem>
     */
    public function getDataItem(): Collection
    {
        return $this->dataItem;
    }

    public function addDataItem(DataItem $dataItem): static
    {
        if (!$this->dataItem->contains($dataItem)) {
            $this->dataItem->add($dataItem);
        }

        return $this;
    }

    public function removeDataItem(DataItem $dataItem): static
    {
        $this->dataItem->removeElement($dataItem);

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
}
