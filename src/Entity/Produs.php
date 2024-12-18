<?php

namespace App\Entity;

use App\Repository\ProdusRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProdusRepository::class)]
class Produs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'denumire', length: 255, nullable: false)]
    #[Groups(['stock', 'linie_document'])]
    private ?string $name = null;

    #[ORM\Column(name: 'pret_vanzare')]
    private ?float $price = null;

    #[ORM\Column(name: "cod_de_bare", length: 255)]
    private ?string $barCode = null;

    #[ORM\OneToOne(mappedBy: 'produs', cascade: ['persist', 'remove'])]
    private ?Stoc $stoc = null;

    #[ORM\OneToOne(mappedBy: 'produs', cascade: ['persist', 'remove'])]
    private ?LinieDocument $linieDocument = null;

    #[ORM\ManyToOne(inversedBy: 'produses')]
    private ?UnitateM $um = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Tva $tva = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getBarCode(): ?string
    {
        return $this->barCode;
    }

    public function setBarCode(string $barCode): static
    {
        $this->barCode = $barCode;

        return $this;
    }

    public function getStoc(): ?Stoc
    {
        return $this->stoc;
    }

    public function setStoc(?Stoc $stoc): static
    {
        // unset the owning side of the relation if necessary
        if ($stoc === null && $this->stoc !== null) {
            $this->stoc->setProdus(null);
        }

        // set the owning side of the relation if necessary
        if ($stoc !== null && $stoc->getProdus() !== $this) {
            $stoc->setProdus($this);
        }

        $this->stoc = $stoc;

        return $this;
    }

    public function getLinieDocument(): ?LinieDocument
    {
        return $this->linieDocument;
    }

    public function setLinieDocument(?LinieDocument $linieDocument): static
    {
        // unset the owning side of the relation if necessary
        if ($linieDocument === null && $this->linieDocument !== null) {
            $this->linieDocument->setProdus(null);
        }

        // set the owning side of the relation if necessary
        if ($linieDocument !== null && $linieDocument->getProdus() !== $this) {
            $linieDocument->setProdus($this);
        }

        $this->linieDocument = $linieDocument;

        return $this;
    }

    public function getUm(): ?UnitateM
    {
        return $this->um;
    }

    public function setUm(?UnitateM $um): static
    {
        $this->um = $um;

        return $this;
    }

    public function getTva(): ?Tva
    {
        return $this->tva;
    }

    public function setTva(?Tva $tva): static
    {
        $this->tva = $tva;

        return $this;
    }
}
