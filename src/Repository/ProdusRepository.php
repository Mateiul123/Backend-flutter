<?php

namespace App\Repository;

use App\Entity\Produs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class ProdusRepository
{
    private ObjectRepository $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Produs::class);
    }

    public function findByExampleField($value): array
    {
        return $this->repository->findBy(['exampleField' => $value]);
    }

    public function findOneBySomeField($value): ?Produs
    {
        return $this->repository->findOneBy(['exampleField' => $value]);
    }
}
