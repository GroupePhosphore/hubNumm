<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class PingController extends AbstractCustomController
{
    #[Route('/ping', name: 'app_ping')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Pong'
        ]);
    }
}
