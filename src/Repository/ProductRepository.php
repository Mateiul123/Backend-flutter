<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class ProductRepository
{
    private ObjectRepository $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Product::class);
    }

    public function findByExampleField($value): array
    {
        return $this->repository->findBy(['exampleField' => $value]);
    }

    public function findOneBySomeField($value): ?Product
    {
        return $this->repository->findOneBy(['exampleField' => $value]);
    }
}
