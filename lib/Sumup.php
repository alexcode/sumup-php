<?php

namespace Sumup;

class Sumup
{
    /**
     * The Sumup client_secret to be used for requests.
     * @var string
     */
    public static $clientSecret;

    /**
     * The Sumup client_id to be used for Connect requests.
     * @var string
     */
    public static $clientId;

    /**
     * The base URL for the Sumup API.
     * @var string
     */
    public static $apiBase = 'https://api.sumup.com';

    /**
     * The version for the Sumup API.
     * @var string
     */
    public static $apiVersion = 'v0.1';

    /**
     * The base URL for the OAuth API.
     * @var string
     */
    public static $connectBase = 'https://api.sumup.com';

    /**
     * The redirect Uri for the OAuth authorize.
     * @var string
     */
    public static $redirectUri;

    /**
     * @return string The apiBase used for requests.
     */
    public static function getApiBase()
    {
        return self::$apiBase;
    }

    /**
     * Sets the apiBase to be used for requests.
     *
     * @param string $apiBase
     */
    public static function setApiBase($apiBase)
    {
        self::$apiBase = $apiBase;
    }

    /**
     * @return string The client_secret used for requests.
     */
    public static function getClientSecret()
    {
        return self::$clientSecret;
    }

    /**
     * Sets the client_secret to be used for requests.
     *
     * @param string $clientSecret
     */
    public static function setClientSecret($clientSecret)
    {
        self::$clientSecret = $clientSecret;
    }

    /**
     * Gets the client_id to be used for Connect requests.
     *
     * @param string $clientId
     */
    public static function getClientId()
    {
        return self::$clientId;
    }

    /**
     * Sets the client_id to be used for Connect requests.
     *
     * @param string $clientId
     */
    public static function setClientId($clientId)
    {
        self::$clientId = $clientId;
    }

    /**
     * Gets the redirect_uri to be used for Connect requests.
     *
     * @param string $redirectUri
     */
    public static function getRedirectUri()
    {
        return self::$redirectUri;
    }

    /**
     * Sets the redirect_uri to be used for Connect requests.
     *
     * @param string $redirectUri
     */
    public static function setRedirectUri($redirectUri)
    {
        self::$redirectUri = $redirectUri;
    }

    /**
     * Gets the API version.
     *
     * @param string $apiVersion
     */
    public static function getApiVersion()
    {
        return self::$apiVersion;
    }

    /**
     * Sets the API version.
     *
     * @param string $apiVersion
     */
    public static function setApiVersion($apiVersion)
    {
        self::$apiVersion = $apiVersion;
    }

    /**
     * SumUp __construct
     * @param string $clientId     Your SumUp Client Id
     * @param string $clientSecret Your SumUp Client Secret
     */
    public function __construct($clientId, $clientSecret)
    {
        $this->setClientId($clientId);
        $this->setClientSecret($clientSecret);
    }
}
