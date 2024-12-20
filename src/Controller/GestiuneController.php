<?php

namespace App\Controller;

use App\Service\DatabaseConnectionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GestiuneController extends AbstractController
{
    public function __construct(private DatabaseConnectionService $dbService,
                                private SerializerInterface $serializerInterface) {}

    #[Route('/api/gestiune', name: 'app_gestiune', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $dbName = $data['dbname'] ?? null;

        if (!$dbName) {
            throw new HttpException(400, "dbname is required in the request body.");
        }

        $em = $this->dbService->getEntityManagerForDb($dbName, entityPaths: [__DIR__ . '/../Entity']);
        $dql = 'SELECT g FROM App\Entity\Gestiune g';
        $query = $em->createQuery($dql);
        
        $gestiuni = $query->getResult();

        if(!$gestiuni) {
            throw new HttpException(404, "Gestiuni not found.");
        }

        $data = $this->serializerInterface->serialize($gestiuni, 'json',  ['groups' => 'gestiune']);
        return new JsonResponse($data, 200, [], true);
    }
}
