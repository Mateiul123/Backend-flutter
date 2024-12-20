<?php

namespace App\Entity;

use App\Repository\StocRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StocRepository::class)]
class Stoc
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['stock'])]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: "id_gestiune", referencedColumnName: "id", nullable: false)]
    #[Groups(['stock'])]
    private ?Gestiune $gestiune = null;

    #[ORM\Column(name: "cantitate")]
    #[Groups(['stock', 'linie_document'])]
    private ?int $initialProductCount = null;

    #[ORM\Column(name: "stoc_initial")]
    #[Groups(['stock', 'linie_document'])]
    private ?int $changedProductCount = null;

    #[ORM\Column(name: "pu")]
    #[Groups(['stock'])]
    private ?int $stockPrice = null;

    #[ORM\OneToOne(inversedBy: 'stoc', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: "id_produs", referencedColumnName: "id", nullable: false)]
    #[Groups(['stock'])]
    private ?Produs $produs = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGestiune(): ?Gestiune
    {
        return $this->gestiune;
    }

    public function setGestiune(?Gestiune $gestiune): static
    {
        $this->gestiune = $gestiune;

        return $this;
    }

    public function getInitialProductCount(): ?int
    {
        return $this->initialProductCount;
    }

    public function setInitialProductCount(int $initialProductCount): static
    {
        $this->initialProductCount = $initialProductCount;

        return $this;
    }

    public function getChangedProductCount(): ?int
    {
        return $this->changedProductCount;
    }

    public function setChangedProductCount(int $changedProductCount): static
    {
        $this->changedProductCount = $changedProductCount;

        return $this;
    }

    public function getStockPrice(): ?int
    {
        return $this->stockPrice;
    }

    public function setStockPrice(int $stockPrice): static
    {
        $this->stockPrice = $stockPrice;

        return $this;
    }

    public function getProdus(): ?Produs
{
    return $this->produs;
}

public function setProdus(?Produs $produs): static
{
    // Asigură-te că relația este setată corect pe ambele părți
    if ($produs !== null && $produs->getStoc() !== $this) {
        $produs->setStoc($this);
    }

    $this->produs = $produs;

    return $this;
}
}
