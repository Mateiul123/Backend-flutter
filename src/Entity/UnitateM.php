<?php

namespace App\Entity;

use App\Repository\UnitateMRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UnitateMRepository::class)]
class UnitateM
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $denumire = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cod = null;

    /**
     * @var Collection<int, Produs>
     */
    #[ORM\OneToMany(mappedBy: 'um', targetEntity: Produs::class)]
    private Collection $produses;

    public function __construct()
    {
        $this->produses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDenumire(): ?string
    {
        return $this->denumire;
    }

    public function setDenumire(?string $denumire): static
    {
        $this->denumire = $denumire;

        return $this;
    }

    public function getCod(): ?string
    {
        return $this->cod;
    }

    public function setCod(?string $cod): static
    {
        $this->cod = $cod;

        return $this;
    }

    /**
     * @return Collection<int, Produs>
     */
    public function getProduses(): Collection
    {
        return $this->produses;
    }

    public function addProdus(Produs $produs): static
    {
        if (!$this->produses->contains($produs)) {
            $this->produses->add($produs);
            $produs->setUm($this);
        }

        return $this;
    }

    public function removeProdus(Produs $produs): static
    {
        if ($this->produses->removeElement($produs)) {
            // set the owning side to null (unless already changed)
            if ($produs->getUm() === $this) {
                $produs->setUm(null);
            }
        }

        return $this;
    }
}
