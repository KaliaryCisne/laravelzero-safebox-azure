<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use stdClass;


abstract class AbstractService
{

    private string $baseURL;
    private Client $client;
    protected bool $associative = false;

    public const BODY_FORMAT_DEFAULT = 'default';
    public const BODY_FORMAT_JSON = 'json';
    public const BODY_FORMAT_FORM = 'form';

    public function __construct(string $baseURL)
    {
        $this->baseURL = $baseURL;
        $this->client = $this->getClient();

    }

    protected function getClient(): Client
    {
        if(!isset($this->client)) {
            $this->client = new Client(['base_uri' => $this->baseURL]);
        }
        return $this->client;
    }

    /**
     * Faz uma requisição do tipo POST
     * @param string $url
     * @param array $data
     * @param array $headers
     * @param string $format
     * @return object
     */
    public function post(string $url, array $data = [], array $headers = [], string $format = self::BODY_FORMAT_DEFAULT) : object
    {
        try {

            $options = ['headers' => $headers];

            switch($format) {
                case self::BODY_FORMAT_JSON:
                    $options['json'] = json_encode($data);
                    break;
                case self::BODY_FORMAT_FORM:
                    $options['form_params'] = $data;
                    break;
                default:
                    $options['body'] = json_encode($data);
            }

            $response = $this->client->request('POST', $url, $options);
            return $this->getResponse($response);
        }
        catch (ClientException $e) {
            throw new RuntimeException($e->getResponse()->getBody()->getContents());
        }
        catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Faz um requisição do tipo GET
     * @param string $url
     * @param array $headers
     * @return object
     */
    public function get(string $url, array $headers = []) : object
    {
        try {

            $options = ['headers' => $headers];

            $response = $this->client->request('GET', $url, $options);
            return $this->getResponse($response);
        }
        catch (ClientException $e) {
            throw new RuntimeException($e->getResponse()->getBody()->getContents());
        }
        catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Faz um requisição do tipo PUT
     * @param string $url
     * @param array $headers
     * @return object
     */
    public function put(string $url, array $headers = []) : object
    {
        try {
            $options = ['headers' => $headers];
            $uri = "{$this->baseURL}{$url}";

            $response = $this->client->request('PUT', $uri, $options);
            return $this->getResponse($response);
        }
        catch (ClientException $e) {
            throw new RuntimeException($e->getResponse()->getBody()->getContents());
        }
        catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Monta a resposta que será enviada para o solicitante
     * @param Response $response
     * @return object
     */
    private function getResponse(Response $response) : object
    {
        $obj = new stdClass;
        try{

            $obj->isOk = $response->getReasonPhrase() == 'OK';

            if(!$obj->isOk) {
                throw new Exception();
            }

            $obj->statusCode = $response->getStatusCode();
            $obj->headers = $response->getHeaders();

            $body = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
            $obj->body = $body ?? null;

        } catch (Exception $e) {

            $headers = $response->getHeaders();
            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

            $obj->isOk = false;
            $obj->statusCode = $statusCode ?? 500;
            $obj->headers = $headers ?? null;
            $obj->body = $body ?? null;
        }
        return $obj;
    }

}
