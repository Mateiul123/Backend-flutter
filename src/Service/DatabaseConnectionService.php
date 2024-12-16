<?php

namespace App\Service;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Symfony\Contracts\Cache\CacheInterface; 
use Symfony\Contracts\Cache\ItemInterface;// Importă interfața CacheInterface

class DatabaseConnectionService
{
    private CacheInterface $cache; // Cache-ul injectat
    private array $connections = []; // Stocăm conexiunile deschise

    /**
     * Constructor privat pentru a preveni instanțierea directă.
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Adaugă o conexiune pentru o anumită bază de date.
     */
    public function addConnection(string $dbName, array $connectionParams): void
    {
        $cacheKey = 'db_connection_' . $dbName;

        // Verifică dacă conexiunea există deja în cache
        $connection = $this->cache->get($cacheKey, function (ItemInterface $item) use ($connectionParams) {
            // Dacă nu există, creează-o și o returnează
            $item->expiresAfter(null);

            $connection = DriverManager::getConnection($connectionParams);
            $connection->connect();

            return $connection->getParams();
        });

        dd($connection);
    }

    /**
     * Creează și returnează un EntityManager personalizat pentru baza de date specificată.
     */
    /**
     * Creează și returnează un EntityManager personalizat pentru baza de date specificată.
     */
    public function getEntityManagerForDb(string $dbName, array $entityPaths): EntityManagerInterface
    {
        // Cheia de cache pentru parametrii de conexiune
        $cacheKey = 'db_connection_' . $dbName;

        // Obține parametrii de conexiune din cache
        $connectionParams = $this->cache->get($cacheKey, function (ItemInterface $item) use ($dbName) {
            // Dacă nu există în cache, aruncăm o excepție (sau poți să tratezi altfel)
            throw new \RuntimeException("No connection parameters found in cache for database '$dbName'.");
        });

        // Creează conexiunea
        $connection = DriverManager::getConnection($connectionParams);

        // Creează configurația pentru EntityManager
        $config = ORMSetup::createAttributeMetadataConfiguration(
            $entityPaths, // Căile către entități
            true, // Modul dev: cache dezactivat
            null,
            null,
            false
        );

        // Crează EntityManager-ul pentru baza de date
        $entityManager = EntityManager::create($connection, $config);

        return $entityManager;
    }



    public function getConnections(): array
    {
        return $this->connections;
    }

    public function setConnection(string $dbName, Connection $connection)
    {
        $this->connections[$dbName] = $connection;
    }
}
