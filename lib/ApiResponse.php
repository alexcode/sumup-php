<?php

namespace Sumup;

use \GuzzleHttp\Psr7\Response;

/**
 * Class ApiResponse
 *
 * @package Sumup
 */
class ApiResponse
{
    public $createdAt;
    public $data;
    public $raw;

    /**
     * @param Response $response
     * @return obj An APIResponse
     */
    public function __construct(Response $response)
    {
        $date = $response->getHeader('date');
        if (is_array($date) && count($date) > 0) {

            $this->createdAt = $date[0];
        }
        $this->raw = $response;
        $this->data = $this->decodeJson($response->getBody());
    }

    public function get($name)
    {
        if (property_exists($this->data, $name)) {
            return $this->data->{$name};
        }
    }

    public function toArray()
    {
        return (array) $this->data;
    }

    private function decodeJson($args) {
        $data = json_decode($args);

        return json_last_error() !== JSON_ERROR_NONE ? $args : $data;
    }
}
