<?php

namespace App\External\Salesforce;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface ;
use GuzzleHttp\{Client, RequestOptions};
use Psr\Log\LoggerInterface;


class SalesforceLoginManager
{
    // Login Credentials
    private string $username;
    private string $password;
    private string $grantType;
    private string $clientId;
    private string $clientSecret;
    private string $nummToken;

    // Salesforce app data
    private string $loginUrl;
    private string $authToken;
    private string $instanceUrl;
    
    protected $logger;
    private Client $authClient;

    public function __construct(ParameterBagInterface  $params, LoggerInterface $logger)
    {
        $this->username = $params->get('app.salesforce.username');
        $this->password = $params->get('app.salesforce.password');
        $this->grantType = $params->get('app.salesforce.grant_type');
        $this->clientId = $params->get('app.salesforce.client_id');
        $this->clientSecret = $params->get('app.salesforce.client_secret');
        $this->loginUrl =  $params->get('app.salesforce.login_url');
        $this->grantType =  $params->get('app.salesforce.grant_type');
        $this->nummToken =  $params->get('app.salesforce.numm_token');
        $this->authClient = new Client(['base_uri' => $this->loginUrl]);
        $this->logger = $logger;
    }

    /**
     * Get Security token and instance url from OAuth service on Salesforce's Api
     *
     * @return void
     */
    public function setSecurityTokenAndInstanceUrl(): void
    {
        try {
            $this->logger->debug("Connecting to Salesforce Api Auth");

            $apiCall = $this->authClient->post(
                '/services/oauth2/token',
                [
                    RequestOptions::FORM_PARAMS => [
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                        'username' => $this->username,
                        'password' => $this->password . $this->nummToken,
                        'grant_type' => $this->grantType
                    ]
                ]
            );

            $data = json_decode($apiCall->getBody(), true);
            $this->instanceUrl = $data['instance_url'];
            $this->authToken = $data['access_token'];

            
            $this->logger->debug("Successfully logged in");
        } catch (Exception $e) {
            $this->logger->debug("Error on login : " . $e->getMessage());

            throw new Exception('Error on Loggin');
        }
        
    }

    public function getInstanceUrl(): string
    {
        if (!isset($this->instanceUrl)) {
            $this->setSecurityTokenAndInstanceUrl();
        }
        return $this->instanceUrl;
    }

    public function getAuthToken(): string
    {
        if (!isset($this->instanceUrl)) {
            $this->setSecurityTokenAndInstanceUrl();
        }
        return $this->authToken;
    }
        
    public function getNummToken(): string
    {
        return $this->nummToken;
    }
}
