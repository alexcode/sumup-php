<?php

namespace Sumup;

/**
 * Class ApiResponse
 *
 * @package Sumup
 */
class AccessToken
{
    public $access_token;
    public $token_type;
    public $expires_at;
    public $refresh_token;
    public $scope;

    /**
     * @param Response $response
     * @return obj An APIResponse
     */
    public function __construct($attributes = null)
    {
        if ($attributes instanceof ApiResponse) {
            $this->setApiResonse($attributes);
        }
        if (is_array($attributes)) {
            $this->setArrayAttributes($attributes);
        }
    }

    protected function setApiResonse(ApiResponse $response)
    {
        $this->access_token = $response->get('access_token');
        $this->token_type = $response->get('token_type');
        $this->expires_at = $this->getTokenExpire(
            $response->get('expires_in'),
            $response->createdAt
        );
        $this->refresh_token = $response->get('refresh_token');
        $this->scope = $response->get('scope');
    }
    protected function setArrayAttributes(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    private function getTokenExpire($expireIn, $requestDate = null)
    {
        $date = new \DateTime($requestDate);
        $interval = 'PT' . $expireIn . 'S';
        $date->add(new \DateInterval($interval));

        return $date;
    }

    public function hasRefreshToken()
    {
        return isset($this->refresh_token);
    }
}
