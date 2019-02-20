<?php

namespace Sumup;

class ApiRequestor
{
    private $_apiBase;
    private static $_httpClient;
    protected $noVersion;
    protected $accessToken;

    public function __construct($apiBase = null, $noVersion = null)
    {
        if (!$apiBase) {
            $apiBase = Sumup::$apiBase;
        }
        $this->noVersion = $noVersion;
        $this->_apiBase = $apiBase;
    }

    public function setAccessToken(AccessToken $token)
    {
        $this->accessToken = $token->access_token;
        $this->tokenType = $token->token_type;

        return $this;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array|null $params
     * @param array|null $headers
     *
     * @return array An array whose first element is an API response and second
     * element is the API key used to make the request.
     */
    public function request($method, $url, $params = null, $version = null, $headers = null)
    {
        $params = $params ?: [];
        $headers = $headers ?: [];
        if ($this->accessToken && $this->tokenType === 'Bearer') {
            $headers['Authorization'] = 'Bearer ' . $this->accessToken;
        }

        $payloadType = strcasecmp($method, 'get') === 0 ? 'query' : 'json';
        $client = $this->httpClient();

        if (!$this->noVersion) {
            $url = Sumup::getApiVersion() . '/' . $url;
        }

        try {
            $response = $client->request($method, $url, [
                $payloadType => $params,
                'headers' => $headers,
                'http_errors' => false
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new Error\ApiRequestorError($e->getMessage());
        }

        return new ApiResponse($response);
    }

    public static function setHttpClient($client)
    {
        self::$_httpClient = $client;
    }

    private function httpClient()
    {
        if (!self::$_httpClient) {
            self::$_httpClient = new \GuzzleHttp\Client([
                'base_uri' => $this->_apiBase
            ]);
        }

        return self::$_httpClient;
    }
}
