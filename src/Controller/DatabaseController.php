<?php

namespace App\Controller;

use App\Service\DatabaseConnection;
use App\Service\DatabaseConnectionService;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class DatabaseController extends AbstractController 
{
    private DatabaseConnectionService $db;

    public function __construct(DatabaseConnectionService $db)
    {
        $this->db = $db; // Instanța este acum injectată corect
    }

    #[Route('/api/database/connect', name: 'app_database_connect', methods: ['POST'])]
    public function connect(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $host = $data['host'] ?? '';
        $port = $data['port'] ?? '3306';
        $dbname = $data['dbname'] ?? '';
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        $connectionParams = [
            'dbname' => $dbname,
            'user' => $username,
            'password' => $password,
            'host' => $host,
            'driver' => 'pdo_sqlsrv',
            'port' => $port,
        ];

        try {
            $this->db->addConnection($connectionParams['dbname'], $connectionParams);
            return new JsonResponse(['status' => 'success', 'message' => 'Conexiune reușită!'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => 'Eroare de conectare: ' . $e->getMessage()], 400);
        }
    }
}
