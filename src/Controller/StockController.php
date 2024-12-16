<?php

namespace App\Controller;

use App\Entity\Management;
use App\Entity\Product;
use App\Entity\Stock;
use App\Service\DatabaseConnectionService;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class StockController extends AbstractController
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
        
        $dql = 'SELECT p FROM App\Entity\Product p WHERE p.barCode = :value';
        $query = $em->createQuery($dql);
        $query->setParameter('value', $data['input']);

        $results = $query->getResult();

        if (!$results[0]) {
            throw new HttpException(404, "Product not found.");
        }

        $stock = $results[0]->getStock();

        // Serializăm și returnăm rezultatul
        $data = $this->serializerInterface->serialize($stock, 'json',  ['groups' => 'stock']);
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/api/stock/product/name', name: 'app_get_name_stock', methods: ['POST'])]
    public function getStockByName(Request $request): JsonResponse
    {
        // Obținem baza de date specificată din body-ul request-ului
        $data = json_decode($request->getContent(), true);
        $dbName = $data['dbname'] ?? null;

        if (!$dbName) {
            throw new HttpException(400, "dbname is required in the request body.");
        }

        $em = $this->dbService->getEntityManagerForDb($dbName, entityPaths: [__DIR__ . '/../Entity']);

        $dql = 'SELECT p FROM App\Entity\Product p WHERE p.name = :value';
        $query = $em->createQuery($dql);
        $query->setParameter('value', $data['input']);

        $results = $query->getResult();

        if (!$results[0]) {
            throw new HttpException(404, "Product not found.");
        }

        $stock = $results[0]->getStock();

        // Serializăm și returnăm rezultatul
        $data = $this->serializerInterface->serialize($stock, 'json',  ['groups' => 'stock']);
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

        $stock = $em->find(Stock::class, $id);

        $stock->setChangedProductCount($data['changedProductCount']);

        $em->flush();

        return new JsonResponse("", 200, []);
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
