<?php

namespace App\Repository;

use App\Entity\Management;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Repository custom pentru Management.
 */
class ManagementRepository
{
    private EntityManagerInterface $entityManager;
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        // Obține repository-ul generic pentru Management
        $this->repository = $this->entityManager->getRepository(Management::class);
    }

    /**
     * Găsește o entitate Management după ID.
     */
    public function find($id): ?Management
    {
        return $this->repository->find($id);
    }

    /**
     * Găsește toate entitățile Management.
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * Găsește entități Management pe baza criteriilor.
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Găsește o singură entitate Management pe baza criteriilor.
     */
    public function findOneBy(array $criteria): ?Management
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Salvează o entitate Management.
     */
    public function save(Management $management): void
    {
        $this->entityManager->persist($management);
        $this->entityManager->flush();
    }

    /**
     * Șterge o entitate Management.
     */
    public function delete(Management $management): void
    {
        $this->entityManager->remove($management);
        $this->entityManager->flush();
    }
}
