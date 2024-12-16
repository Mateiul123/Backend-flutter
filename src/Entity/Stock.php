<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['stock'])]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(['stock'])]
    private ?Management $management = null;

    #[ORM\Column(name: "initial_product_count")]
    #[Groups(['stock'])]
    private ?int $initialProductCount = null;

    #[ORM\Column(name: "changed_product_count")]
    #[Groups(['stock'])]
    private ?int $changedProductCount = null;

    #[ORM\Column(name: "stock_price")]
    #[Groups(['stock'])]
    private ?int $stockPrice = null;

    #[ORM\OneToOne(inversedBy: 'stock', cascade: ['persist', 'remove'])]
    #[Groups(['stock'])]
    private ?Product $product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getManagement(): ?Management
    {
        return $this->management;
    }

    public function setManagement(?Management $management): static
    {
        $this->management = $management;

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

    public function getProduct(): ?product
    {
        return $this->product;
    }

    public function setProduct(?product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
