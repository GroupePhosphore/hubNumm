<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\External\Salesforce\SalesforceClient;

#[Route('/api/conseiller', name: 'api_')]
class ConseillerController extends AbstractCustomController
{
    #[Route('/get', name: 'getAllConseillers', methods: ['POST'])]
    public function getAllConseillers(Request $request, SalesforceClient $client)
    {
        $firstCall = $client->fetch->getAllConseillers();
        $data = $firstCall->records;
        $nextRecordsUrl = isset($firstCall->nextRecordsUrl) ? $firstCall->nextRecordsUrl : null;

        while ($nextRecordsUrl) {
            $call = $client->fetch->getEagerResult($nextRecordsUrl);
            $data = array_merge($data, $call->records);
            $nextRecordsUrl = isset($data->nextRecordsUrl) ? $data->nextRecordsUrl : null;
        }

        return $this->json([
            'message' => 'Success',
            'data' => $data
        ]);
    }

    #[Route('/getByIdDatalake', name: 'getConseillerByIdDatalake', methods: ['POST'])]
    public function getSingleConseillerByIdDatalake(Request $request, SalesforceClient $client)
    {
        $datalakeId = $request->get('datalakeId');

        if (!$datalakeId) {
            return $this->json([
                'message' => 'Error',
                'details' => 'Missing Datalake ID'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        $call = $client->fetch->getConseillerByIdDatalake($datalakeId);

        if (!count($call->records)) {
            return $this->json([
                'message' => 'Not found',
                'details' => 'No data was found for this ID'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json([
            'message' => 'Success',
            'data' => $call->records[0]
        ]);
    }
}
