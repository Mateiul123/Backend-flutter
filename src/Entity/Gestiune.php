<?php

namespace App\Entity;

use App\Repository\GestiuneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GestiuneRepository::class)]
class Gestiune
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['gestiune'])]
    private ?int $id = null;

    #[ORM\Column(name: 'denumire', length: 255)]
    #[Groups(['stock'. 'gestiune'])]
    private ?string $name = null;

    /**
     * @var Collection<int, Document>
     */
    #[ORM\OneToMany(mappedBy: 'gestiune', targetEntity: Document::class)]
    private Collection $documents;

    /**
     * @var Collection<int, LinieDocument>
     */
    #[ORM\OneToMany(mappedBy: 'gestiune', targetEntity: LinieDocument::class)]
    private Collection $linieDocuments;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->linieDocuments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setGestiune($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): static
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getGestiune() === $this) {
                $document->setGestiune(null);
            }
        }

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
            $linieDocument->setGestiune($this);
        }

        return $this;
    }

    public function removeLinieDocument(LinieDocument $linieDocument): static
    {
        if ($this->linieDocuments->removeElement($linieDocument)) {
            // set the owning side to null (unless already changed)
            if ($linieDocument->getGestiune() === $this) {
                $linieDocument->setGestiune(null);
            }
        }

        return $this;
    }
}
