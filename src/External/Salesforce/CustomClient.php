<?php

namespace App\External\Salesforce;

use GuzzleHttp\{Client, RequestOptions};

class CustomClient extends Client
{
    public function __construct(
        private string $baseUri,
        private string $authToken,
        private string $nummToken
    ) {
        parent::__construct([
            'base_uri' => $this->baseUri,
        ]);
    }

    public function makeRequest(
        string $method,
        string $url,
        ?array $body = null
    ) {
        $options[RequestOptions::HEADERS] = [
            'Authorization' => 'Bearer ' . $this->authToken,
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        if ($body) {
            $options[RequestOptions::JSON] = $body;
        }

        $call = $this->request(
            $method,
            $url,
            $options
        );

        return json_decode($call->getBody());
    }
}
