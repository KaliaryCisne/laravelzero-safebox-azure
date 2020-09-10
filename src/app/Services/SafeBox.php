<?php


namespace App\Services;


class SafeBox extends AbstractService
{

    public function __construct(string $baseURL)
    {
        parent::__construct($baseURL);
    }

    /**
     * Realiza a autenticacao no cofre
     * @return object
     */
    public function login(){
        $tenant_id = env('AZURE_TENANT_ID');
        $url = "https://login.microsoftonline.com/{$tenant_id}/oauth2/token";
        $data = [
            'client_id'     => env('AZURE_CLIENT_ID'),
            'client_secret' => env('AZURE_CLIENT_SECRET'),
            'resource'      => 'https://vault.azure.net',
            'grant_type'    => 'client_credentials'
        ];

        $response = $this->post($url, $data, [], AbstractService::BODY_FORMAT_FORM) ;
        return $response;
    }

    /**
     * Busca as variáveis de cofre da azure
     * @param array $variables
     * @return |null
     */
    public function getVariables(array $variables)
    {
        try {
            $response = $this->login();
            if (!$response->isOk) {
                throw new \Exception('Não foi possível se autenticar no cofre.');
            }

            $token = $response->body->access_token;
            $keyVaultName = env('KEY_VAULT_NAME');
            $headers = [
                'Authorization' => "Bearer {$token}"
            ];

            foreach ($variables as $variable) {
                $url = "https://{$keyVaultName}.vault.azure.net/secrets/{$variable}?api-version=2016-10-01";
                $response = $this->get($url, $headers);

                $variable = str_replace("-", "_", $variable);

                putenv("{$variable}={$response->body->value}");
            }
        } catch (\Exception $e) {
            echo $e;
            return null;
        }
    }
}
