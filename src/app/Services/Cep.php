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
        try {
            $response = $this->get($endpoint);
            return $response;
        } catch (\Exception $e) {
            return false;
        }
    }

}
