<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\External\Salesforce\SalesforceClient;

#[Route('/api/conseiller', name: 'api_')]
class ConseillerController extends AbstractCustomController
{
    #[Route('/get', name: 'getAllConseillers')]
    public function getAllConseillers(Request $request, SalesforceClient $client)
    {
        $paginationLimit = $request->get('paginationLimit');
        $paginationOffset = $request->get('paginationOffset');
        dd($client->fetch->getAllConseillers($paginationLimit, $paginationOffset));
    }
}
