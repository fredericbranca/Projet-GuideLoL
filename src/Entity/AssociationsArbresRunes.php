<?php

namespace App\Entity;

use App\Repository\AssociationsArbresRunesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssociationsArbresRunesRepository::class)]
class AssociationsArbresRunes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $type = null;

    #[ORM\ManyToMany(targetEntity: DataRune::class)]
    #[ORM\JoinTable(name: "choix_runes")]
    private Collection $choixRunes;

    public function __construct()
    {
        $this->choixRunes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, DataRune>
     */
    public function getChoixRunes(): Collection
    {
        return $this->choixRunes;
    }

    public function addChoixRune(DataRune $choixRune): static
    {
        if (!$this->choixRunes->contains($choixRune)) {
            $this->choixRunes->add($choixRune);
        }

        return $this;
    }

    public function removeChoixRune(DataRune $choixRune): static
    {
        $this->choixRunes->removeElement($choixRune);

        return $this;
    }
}
