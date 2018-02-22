<?php

namespace Sumup;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase as BaseTestCase;
/**
 * Base class for SumUp test cases.
 */
class TestCase extends BaseTestCase
{
    /** @var string original API base URL */
    protected $origApiBase;
    /** @var string original API key */
    protected $origClientSecret;
    /** @var string original client ID */
    protected $origClientId;
    /** @var string original API version */
    protected $origApiVersion;
    /** @var object HTTP client mocker */
    protected $clientMock;

    protected function setUp()
    {
        // Save original values so that we can restore them after running tests
        $this->origApiBase = Sumup::getApiBase();
        $this->origClientSecret = Sumup::getClientSecret();
        $this->origClientId = Sumup::getClientId();
        $this->origApiVersion = Sumup::getApiVersion();

        // Set up host and credentials for sumup-mock
        $apiBase = 'https://localhost';
        Sumup::setApiBase($apiBase);
        Sumup::setClientSecret('sk_test_123');
        Sumup::setClientId('ca_123');
        Sumup::setApiVersion('v0.1');
    }
    protected function tearDown()
    {
        // Restore original values
        Sumup::setApiBase($this->origApiBase);
        Sumup::setClientSecret($this->origClientSecret);
        Sumup::setClientId($this->origClientId);
        Sumup::setApiVersion($this->origApiVersion);
    }

    /**
     * [setMockClient description]
     * @param array $mockStack List of Response and/or Request
     * [
     *     new Response(200, ['X-Foo' => 'Bar']),
     *     new Response(202, ['Content-Length' => 0]),
     *     new RequestException("Error Communicating with Server", new Request('GET', 'test'))
     * ]
     */
    public function setMockClient(array $mockStack)
    {
        $mock = new MockHandler($mockStack);
        $handler = HandlerStack::create($mock);
        ApiRequestor::setHttpClient(new Client(['handler' => $handler]));
    }

    public function getMockedToken()
    {
        return new AccessToken([
            'access_token' => 'mock_token',
            'token_type' => 'Bearer',
            'expires_at' => new \DateTime('tomorrow'),
            'refresh_token' => 'mock_refresh_token',
            'scope' => 'payments',
        ]);
    }
}
