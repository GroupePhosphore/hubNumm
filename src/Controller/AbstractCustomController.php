<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use OpenApi\Annotations as OA;



/**
 * @OA\Info(
 * 	title="HubNumm",
 * 	version="0.1"
 * 	)
 * @OA\Server(
 * 	url="http://test.com",
 * 	description="HubNumm"
 * 	)
 */
abstract class AbstractCustomController extends AbstractController
{
    public function __construct(private LoggerInterface $logger)
    {
    }
}