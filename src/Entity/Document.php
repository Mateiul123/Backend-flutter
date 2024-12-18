<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $data = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    #[ORM\JoinColumn(name: "id_tip_document", referencedColumnName: "id", nullable: false)]
    private ?TipDocument $tipDocument = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    #[ORM\JoinColumn(name: "id_gestiune", referencedColumnName: "id", nullable: false)]
    private ?Gestiune $gestiune = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $serie = null;

    #[ORM\Column(nullable: true)]
    private ?int $numar = null;

    #[ORM\Column(name: "nr_receptie", length: 255, nullable: true)]
    private ?string $nrReceptie = null;

    #[ORM\Column(nullable: true)]
    private ?float $total = null;

    #[ORM\Column(name: "id_punct_lucru", nullable: true)]
    private ?int $idPunctLucru = null;

    /**
     * @var Collection<int, LinieDocument>
     */
    #[ORM\OneToMany(mappedBy: 'document', targetEntity: LinieDocument::class)]
    private Collection $linieDocuments;

    public function __construct()
    {
        $this->linieDocuments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }

    public function setData(?\DateTimeInterface $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getTipDocument(): ?TipDocument
    {
        return $this->tipDocument;
    }

    public function setTipDocument(?TipDocument $tipDocument): static
    {
        $this->tipDocument = $tipDocument;

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

    public function getSerie(): ?string
    {
        return $this->serie;
    }

    public function setSerie(?string $serie): static
    {
        $this->serie = $serie;

        return $this;
    }

    public function getNumar(): ?int
    {
        return $this->numar;
    }

    public function setNumar(?int $numar): static
    {
        $this->numar = $numar;

        return $this;
    }

    public function getNrReceptie(): ?string
    {
        return $this->nrReceptie;
    }

    public function setNrReceptie(?string $nrReceptie): static
    {
        $this->nrReceptie = $nrReceptie;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(?float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getIdPunctLucru(): ?int
    {
        return $this->idPunctLucru;
    }

    public function setIdPunctLucru(?int $idPunctLucru): static
    {
        $this->idPunctLucru = $idPunctLucru;

        return $this;
    }

    /**
     * @return Collection<int, LinieDocument>
     */
    public function getLinieDocuments(): Collection
    {
        return $this->linieDocuments;
    }

    public function addLinieDocument(LinieDocument $linieDocument): static
    {
        if (!$this->linieDocuments->contains($linieDocument)) {
            $this->linieDocuments->add($linieDocument);
            $linieDocument->setDocument($this);
        }

        return $this;
    }

    public function removeLinieDocument(LinieDocument $linieDocument): static
    {
        if ($this->linieDocuments->removeElement($linieDocument)) {
            // set the owning side to null (unless already changed)
            if ($linieDocument->getDocument() === $this) {
                $linieDocument->setDocument(null);
            }
        }

        return $this;
    }
}
