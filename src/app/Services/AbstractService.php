<?php

namespace App\Services;

use GuzzleHttp\Client;

abstract class AbstractService
{

    private string $baseURL;
    private Client $client;
    protected  bool $associative = false;

    public function __construct(string $baseURL)
    {
        $this->baseURL = $baseURL;
    }

    protected function client(): Client
    {
        if(!isset($this->client)) {
            $this->client = new Client(['base_uri' => $this->baseURL]);
        }
        return $this->client;
    }

    protected function execute(string $method, string $uri, array $options = [])
    {
        try {
            $response = $this->client()->request($method, $uri, $options);
            $json = (string)$response->getBody()->getContents();
            return json_decode($json, $this->associative, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $throwable) {
        }
        return null;
    }

    protected function post(string $uri, array $options = [])
    {
        try {
            $response = $this->client()->post($uri, $options);
            $json = (string)$response->getBody()->getContents();
            return json_decode($json, $this->associative, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $throwable) {
        }
        return null;
    }


    abstract protected function request(string $method, string $endpoint, array $data = []);

}
