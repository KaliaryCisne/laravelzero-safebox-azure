<?php


namespace App\Services;


class Cep extends AbstractService
{

    public function __construct()
    {
        parent::__construct(env('VIA_CEP_URL'));
    }

    public function getCEP(string $endpoint)
    {
        $response = $this->request('GET', $endpoint);
        return $response;
    }

    protected function request(string $method, string $endpoint, array $data = [], array $headers = [])
    {
        $uri = "{$endpoint}";
        $options = [
            'headers' => $headers,
            'data' => $data,
            'http_errors' => true
        ];
        return $this->execute($method, $uri, $options);
    }
}
