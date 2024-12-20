<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Gestiune;
use App\Entity\LinieDocument;
use App\Entity\Produs;
use App\Service\DatabaseConnectionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class LinieDocumentController extends AbstractController
{
    public function __construct(private DatabaseConnectionService $dbService,
                                private SerializerInterface $serializerInterface) {}


    #[Route('/api/linie/document', name: 'app_linie_document', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        if(empty($id)) {
            throw new HttpException(404, "Nu ai trimis un id de produs pentru a returna liniile de comanda!");
        }

        $data = json_decode($request->getContent(), true);
        $dbName = $data['dbname'] ?? null;

        if (!$dbName) {
            throw new HttpException(400, "dbname is required in the request body.");
        }

        $em = $this->dbService->getEntityManagerForDb($dbName, entityPaths: [__DIR__ . '/../Entity']);

        $dql = 'SELECT ld FROM App\Entity\LinieDocument ld JOIN ld.document d WHERE d.id = :value';
        $query = $em->createQuery($dql);
        $query->setParameter('value', $data['input']);

        $liniiDocument = $query->getResult();

        if(!$liniiDocument) {
            throw new HttpException(404, "Liniile de document not found.");
        }

        $data = $this->serializerInterface->serialize($liniiDocument, 'json',  ['groups' => 'linie_document']);
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/api/linie/document/add', methods: ['POST'])]
    public function add(Request $request): JsonResponse 
    {
        $data = json_decode($request->getContent(), true);
        $dbName = $data['dbname'] ?? null;

        if (!$dbName) {
            throw new HttpException(400, "dbname is required in the request body.");
        }

        $em = $this->dbService->getEntityManagerForDb($dbName, entityPaths: [__DIR__ . '/../Entity']);

        $document = $em->find(Document::class, $data['document']);
        $gestiune = $em->find(Gestiune::class, $data['gestiune']);
        $produs = $em->find(Produs::class, $data['produs']);
        
        $dql = 'SELECT s FROM App\Entity\Stoc s WHERE s.produs = :value';
        $query = $em->createQuery($dql);
        $query->setParameter('value', $produs);

        $stoc = $query->getResult();

        $linieDocument = new LinieDocument();

        $linieDocument->setCantitate($data['cantitate']);
        $linieDocument->setDenumire($data['denumire']);
        $linieDocument->setDocument($document);
        $linieDocument->setGestiune($gestiune);
        $linieDocument->setPretVanzareCurent($produs->getPrice());
        $linieDocument->setProdus($produs);
        $linieDocument->setPu($data['pu']);
        $linieDocument->setPuAprox($data['pu']);
        $linieDocument->setPuStoc($data['pu']);
        $linieDocument->setTvaCumparare($produs->getTva()->getId());
        $linieDocument->setUm($produs->getUm()->getId());
        $linieDocument->setValoare($data['cantitate'] * $linieDocument->getPu());
        $linieDocument->setValoareTva(0);
        $linieDocument->setValTvaCumparare(0);

        $em->persist($linieDocument);

        $em->flush();

        return new JsonResponse("", 200);
    }
}
