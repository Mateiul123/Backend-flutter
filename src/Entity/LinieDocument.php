<?php

namespace App\Entity;

use App\Repository\LinieDocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LinieDocumentRepository::class)]
class LinieDocument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'linieDocument', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: "id_produs", referencedColumnName: "id", nullable: false)]
    private ?Produs $produs = null;

    #[ORM\ManyToOne(inversedBy: 'linieDocuments')]
    #[ORM\JoinColumn(name: "id_gestiune", referencedColumnName: "id", nullable: false)]
    private ?Gestiune $gestiune = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $denumire = null;

    #[ORM\Column(nullable: true)]
    private ?int $cantitate = null;

    #[ORM\Column(nullable: true)]
    private ?int $pu = null;

    #[ORM\Column(nullable: true)]
    private ?int $valoare = null;

    #[ORM\Column(name: "valoare_tva", nullable: true)]
    private ?int $valoareTva = null;

    #[ORM\Column(name: "pu_stoc", nullable: true)]
    private ?int $puStoc = null;

    #[ORM\Column(name: "tva_cumparare", nullable: true)]
    private ?int $tvaCumparare = null;

    #[ORM\Column(name: "val_tva_cumparare", nullable: true)]
    private ?int $valTvaCumparare = null;

    #[ORM\Column(name: "pu_aprox", nullable: true)]
    private ?int $puAprox = null;

    #[ORM\Column(name: "pret_vanzare_curent", nullable: true)]
    private ?int $pretVanzareCurent = null;

    #[ORM\ManyToOne(inversedBy: 'linieDocuments')]
    #[ORM\JoinColumn(name: "id_document", referencedColumnName: "id", nullable: false)]
    private ?Document $document = null;

    #[ORM\Column(nullable: true)]
    private ?int $tva = null;

    #[ORM\Column(nullable: true)]
    private ?int $um = null;

    public function __construct()
    {
        $this->linieDocuments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProdus(): ?Produs
    {
        return $this->produs;
    }

    public function setProdus(?Produs $produs): static
    {
        $this->produs = $produs;

        return $this;
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

    public function getDenumire(): ?string
    {
        return $this->denumire;
    }

    public function setDenumire(?string $denumire): static
    {
        $this->denumire = $denumire;

        return $this;
    }

    public function getCantitate(): ?int
    {
        return $this->cantitate;
    }

    public function setCantitate(?int $cantitate): static
    {
        $this->cantitate = $cantitate;

        return $this;
    }

    public function getPu(): ?int
    {
        return $this->pu;
    }

    public function setPu(?int $pu): static
    {
        $this->pu = $pu;

        return $this;
    }

    public function getValoare(): ?int
    {
        return $this->valoare;
    }

    public function setValoare(int $valoare): static
    {
        $this->valoare = $valoare;

        return $this;
    }

    public function getValoareTva(): ?int
    {
        return $this->valoareTva;
    }

    public function setValoareTva(?int $valoareTva): static
    {
        $this->valoareTva = $valoareTva;

        return $this;
    }

    public function getPuStoc(): ?int
    {
        return $this->puStoc;
    }

    public function setPuStoc(?int $puStoc): static
    {
        $this->puStoc = $puStoc;

        return $this;
    }

    public function getTvaCumparare(): ?int
    {
        return $this->tvaCumparare;
    }

    public function setTvaCumparare(?int $tvaCumparare): static
    {
        $this->tvaCumparare = $tvaCumparare;

        return $this;
    }

    public function getValTvaCumparare(): ?int
    {
        return $this->valTvaCumparare;
    }

    public function setValTvaCumparare(?int $valTvaCumparare): static
    {
        $this->valTvaCumparare = $valTvaCumparare;

        return $this;
    }

    public function getPuAprox(): ?int
    {
        return $this->puAprox;
    }

    public function setPuAprox(?int $puAprox): static
    {
        $this->puAprox = $puAprox;

        return $this;
    }

    public function getPretVanzareCurent(): ?int
    {
        return $this->pretVanzareCurent;
    }

    public function setPretVanzareCurent(?int $pretVanzareCurent): static
    {
        $this->pretVanzareCurent = $pretVanzareCurent;

        return $this;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): static
    {
        $this->document = $document;

        return $this;
    }

    public function getTva(): ?int
    {
        return $this->tva;
    }

    public function setTva(?int $tva): static
    {
        $this->tva = $tva;

        return $this;
    }

    public function getUm(): ?int
    {
        return $this->um;
    }

    public function setUm(?int $um): static
    {
        $this->um = $um;

        return $this;
    }
}
