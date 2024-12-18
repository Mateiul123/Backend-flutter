<?php

namespace App\Entity;

use App\Repository\TvaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TvaRepository::class)]
class Tva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $valoare = null;

    #[ORM\Column(name: "grupa_casa_marcat", length: 255, nullable: true)]
    private ?string $grupaCasaMarcat = null;

    #[ORM\Column(name: "id_casa_marcat", nullable: true)]
    private ?int $idCasaMarcat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValoare(): ?float
    {
        return $this->valoare;
    }

    public function setValoare(?float $valoare): static
    {
        $this->valoare = $valoare;

        return $this;
    }

    public function getGrupaCasaMarcat(): ?string
    {
        return $this->grupaCasaMarcat;
    }

    public function setGrupaCasaMarcat(?string $grupaCasaMarcat): static
    {
        $this->grupaCasaMarcat = $grupaCasaMarcat;

        return $this;
    }

    public function getIdCasaMarcat(): ?int
    {
        return $this->idCasaMarcat;
    }

    public function setIdCasaMarcat(?int $idCasaMarcat): static
    {
        $this->idCasaMarcat = $idCasaMarcat;

        return $this;
    }
}
