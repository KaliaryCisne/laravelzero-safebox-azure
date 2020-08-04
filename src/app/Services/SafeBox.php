<?php


namespace App\Services;


class SafeBox extends AbstractService
{
    public function __construct(string $baseURL)
    {
        parent::__construct($baseURL);
    }

    public function login(){
        $tenant_id = env('AZURE_TENANT_ID');
        $url = "https://login.microsoftonline.com/{$tenant_id}/oauth2/token";
        $data = [
            'form_params' => [
                'client_id'     => env('AZURE_CLIENT_ID'),
                'client_secret' => env('AZURE_CLIENT_SECRET'),
                'resource'      => 'https://vault.azure.net',
                'grant_type'    => 'client_credentials',
            ]
        ];

//        $headers = [
//            'Content-Type' => 'multipart/form-data'
//        ];

        $response = $this->post($url, $data);
        return $response;

    }

    public function getVariables(array $variables)
    {
        $response = $this->login();
        $token = $response->access_token;
        $keyVaultName = env('KEY_VAULT_NAME');
        $headers = [
            'Authorization' => "Bearer {$token}"
        ];

        $values = [];
        foreach ($variables as $variable) {
            $url = "https://{$keyVaultName}.vault.azure.net/secrets/{$variable}?api-version=2016-10-01";
            $response = $this->request('GET', $url, [], $headers);
            putenv("{$variable}={$response->value}");
        }


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
