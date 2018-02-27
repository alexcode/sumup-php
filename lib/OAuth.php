<?php

namespace Sumup;

abstract class OAuth
{
    /**
     * Generates a URL to Sumup's OAuth form.
     *
     * @param array|null $params
     * @param array|null $opts
     *
     * @return string The URL to Sumup's OAuth form.
     */
    public static function authorizeUrl($params = null)
    {
        if (!$params) {
            $params = array();
        }

        $params['client_id'] = self::_getConnectParam('client_id', $params);
        $params['redirect_uri'] = self::_getConnectParam('redirect_uri', $params);
        if (!array_key_exists('response_type', $params)) {
            $params['response_type'] = 'code';
        }
        $query = Util\Util::urlEncode($params);

        return Sumup::$connectBase . '/authorize?' . $query;
    }
    /**
     * Use an authoriztion code to connect an account to your platform and
     * fetch the user's credentials.
     *
     * @param array|null $params
     * @param array|null $opts
     *
     * @return SumupObject Object containing the response from the API.
     */
    public static function getToken($params = null)
    {
        if (!$params) {
            $params = array();
        }
        $requestor = new ApiRequestor(Sumup::$connectBase, true);
        $params['client_id'] = self::_getConnectParam('client_id', $params);
        $params['client_secret'] = self::_getConnectParam('client_secret', $params);
        $response = $requestor->request('post', '/token', $params);

        if ($response->raw->getStatusCode() !== 200) {
            throw new Error\AuthenticationError('Token request error');
        }

        return new AccessToken($response);
    }
    /**
     * Use an authoriztion code to connect an account to your platform and
     * fetch the user's credentials.
     *
     * @param array|null $params
     * @param array|null $opts
     *
     * @return SumupObject Object containing the response from the API.
     */
    public static function refreshToken(AccessToken $token)
    {
        if ($token->expires_at > new \DateTime()) {
            return $token;
        }

        $requestor = new ApiRequestor(Sumup::$connectBase, true);
        $params = [
            'grant_type' => 'refresh_token',
            'client_id' => self::_getConnectParam('client_id'),
            'client_secret' => self::_getConnectParam('client_secret'),
            'refresh_token' => $token->refresh_token
        ];
        $response = $requestor->request('post', '/token', $params);

        if ($response->raw->getStatusCode() !== 200) {
            throw new Error\AuthenticationError('Token request error');
        }

        return new AccessToken($response);
    }
    /**
     * return the Connect param based on the name
     * @param  string $paramName
     * @param  array $params
     * @return string
     */
    private static function _getConnectParam($paramName, $params = null)
    {
        $oauthParam = ($params && array_key_exists($paramName, $params))
        ? $params[$paramName] : null;

        if ($oauthParam === null) {
            $funcName = 'get' . Util\Util::toCamelCase($paramName, true);
            $oauthParam = Sumup::{$funcName}();
        }

        if ($oauthParam === null) {
            $msg = 'No '.$paramName.' provided. You can find your '. $paramName
            . ' in your Sumup dashboard at '
            . 'https://me.sumup.com/developers, '
            . 'after registering your account as a platform. See '
            . 'https://sumupus.desk.com/ for details, '
            . 'or email support@sumup.com if you have any questions.';
            throw new Error\AuthenticationError($msg);
        }

        return $oauthParam;
    }
}
