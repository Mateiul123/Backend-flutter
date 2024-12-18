<?php

namespace App\Controller;

use App\Entity\Gestiune;
use App\Entity\Produs;
use App\Entity\Stoc;
use App\Service\DatabaseConnectionService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class StocController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializerInterface,
        private ManagerRegistry $managerRegistry,
        private DatabaseConnectionService $dbService
    ) {
        
    }

    #[Route('/api/stock/product/barcode', name: 'app_get_barcode_stock', methods: ['POST'])]
    public function getStockByBarCode(Request $request): JsonResponse   
    {
        $data = json_decode($request->getContent(), true);
        $dbName = $data['dbname'] ?? null;

        if (!$dbName) {
            throw new HttpException(400, "dbname is required in the request body.");
        }

        $em = $this->dbService->getEntityManagerForDb($dbName, entityPaths: [__DIR__ . '/../Entity']);
        
        $dql = 'SELECT p FROM App\Entity\Produs p WHERE p.barCode = :value';
        $query = $em->createQuery($dql);
        $query->setParameter('value', $data['input']);

        $results = $query->getResult();

        if (!$results) {
            throw new HttpException(404, "Product not found.");
        }

        $dql = 'SELECT s FROM App\Entity\Stoc s WHERE s.produs = :value';
        $query = $em->createQuery($dql);
        $query->setParameter('value', $results[0]->getId());

        $stoc = $query->getResult();
        
        if (!$stoc) {
            throw new HttpException(404, "Stoc not found.");
        }

        // Serializăm și returnăm rezultatul
        $data = $this->serializerInterface->serialize($stoc[0], 'json',  ['groups' => 'stock']);
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/api/stock/product/name', name: 'app_get_name_stock', methods: ['POST'])]
    public function getStocByName(Request $request): JsonResponse
    {
        // Obținem baza de date specificată din body-ul request-ului
        $data = json_decode($request->getContent(), true);
        $dbName = $data['dbname'] ?? null;

        if (!$dbName) {
            throw new HttpException(400, "dbname is required in the request body.");
        }

        $em = $this->dbService->getEntityManagerForDb($dbName, entityPaths: [__DIR__ . '/../Entity']);

        $dql = 'SELECT p FROM App\Entity\Produs p WHERE p.name = :value';
        $query = $em->createQuery($dql);
        $query->setParameter('value', $data['input']);

        $results = $query->getResult();

        if (!$results) {
            throw new HttpException(404, "Product not found.");
        }

        $dql = 'SELECT s FROM App\Entity\Stoc s WHERE s.produs = :value';
        $query = $em->createQuery($dql);
        $query->setParameter('value', $results[0]->getId());

        $stoc = $query->getResult();

        if (!$stoc) {
            throw new HttpException(404, "Stoc not found.");
        }

        // Serializăm și returnăm rezultatul
        $data = $this->serializerInterface->serialize($stoc[0], 'json',  ['groups' => 'stock']);
        return new JsonResponse($data, 200, [], true);
    }

    private function getEntityManagerForDb(string $dbName): EntityManagerInterface
    {
        return $this->managerRegistry->getManager($dbName); 
    }

    #[Route('/api/stock/actual-product-count/{id}', name: 'app_edit_changed_product_count_stock', methods: ['PUT'])]
    public function editChangedProductCount(string $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), 1);
     
        $dbName = $data['dbname'] ?? null;

        if (!$dbName) {
            throw new HttpException(400, "dbname is required in the request body.");
        }
    
        if(empty($data['changedProductCount']) || !is_numeric($data['changedProductCount']) || intval($data['changedProductCount']) != $data['changedProductCount']) {
            throw new HttpException(401, "Eroare");
        }

        $em = $this->dbService->getEntityManagerForDb($dbName, entityPaths: [__DIR__ . '/../Entity']);

        $stock = $em->find(Stoc::class, $id);

        $stock->setInitialProductCount($data['changedProductCount']);

        $stock->setChangedProductCount(0);

        $em->flush();

        return new JsonResponse("", 200, []);
    }

    #[Route('api/produs', methods: ['GET'])]
    public function getProdus()
    {
        $dbname = 'Restaurant_gest';

        $em = $this->dbService->getEntityManagerForDb($dbname, entityPaths: [__DIR__ . '/../Entity']);

        $dql = 'SELECT s FROM App\Entity\Stoc s WHERE s.produs = :value';
        $query = $em->createQuery($dql);
        $query->setParameter('value', 16);

        $result = $query->getResult();

        dd($result);
    }

    // #[Route('/api/stock/initial-product-count/{id}', name: 'app_edit_initial_product_count_stock', methods: ['PUT'])]
    // public function editInitialProductCount(string $id, Request $request): JsonResponse
    // {
    //     $stock = $this->entityManager->getRepository(Stock::class)->find($id);

    //     $stock->setInitialProductCount($stock->getChangedProductCount());

    //     $stock->setChangedProductCount(0);

    //     $this->entityManager->flush();

    //     return new JsonResponse("", 200, []);
    // }
}
