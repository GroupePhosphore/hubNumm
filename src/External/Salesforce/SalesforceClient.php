<?php

namespace App\External\Salesforce;

use App\External\Salesforce\Utils\QueryUtils;
use Exception;
use GuzzleHttp\{Client, RequestOptions};
use Psr\Log\LoggerInterface;


class SalesforceClient
{
    // Salesforce app data
    private string $authToken;
    private string $instanceUrl;
    private string $nummToken;
    
    public CustomClient $nummClient;
    public SalesforceProvider $fetch;

    public function __construct(
        private SalesforceLoginManager $loginManager,
        private LoggerInterface $logger
        )
    {
        $this->loginManager = $loginManager;
        $this->setAuth();
        $this->logger = $logger;
        $this->nummClient = new CustomClient($this->instanceUrl, $this->authToken, $this->nummToken);
        $this->fetch = new SalesforceProvider($this->nummClient, $this->logger);
    }

    private function setAuth(): void
    {
        $this->authToken = $this->loginManager->getAuthToken();
        $this->instanceUrl = $this->loginManager->getInstanceUrl();
        $this->nummToken = $this->loginManager->getNummToken();
    }

}
