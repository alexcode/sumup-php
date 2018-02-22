<?php

namespace Sumup;

use GuzzleHttp\Psr7\Response;
// use GuzzleHttp\Stream\Stream;

/**
 * Base class for SumUp test cases.
 */
class OAuthTest extends TestCase
{
    protected $redirecUri = 'https://example.com/redirect';
    protected $accessToken = 'mock_token';
    protected $refreshToken = 'mock_refresh_token';
    protected $scopes = 'payments';

    public function testAuthorizeUrl()
    {
        $uriStr = OAuth::authorizeUrl([
            'redirect_uri' => $this->redirecUri
        ]);
        $uri = parse_url($uriStr);
        parse_str($uri['query'], $params);

        $this->assertSame('https', $uri['scheme']);
        $this->assertSame('api.sumup.com', $uri['host']);
        $this->assertSame('/authorize', $uri['path']);

        $this->assertSame('ca_123', $params['client_id']);
        $this->assertSame($this->redirecUri, $params['redirect_uri']);
        $this->assertSame('code', $params['response_type']);
    }

    public function testGetToken()
    {
        $body = json_encode([
            'access_token' => $this->accessToken,
            'token_type' => 'Bearer',
            'expires_in' => 3600,
            'refresh_token' => $this->refreshToken,
            'scope' => $this->scopes,
        ]);
        $this->setMockClient([
            new Response(200, ['Content-Type' => 'application/json'], $body),
        ]);

        $token = OAuth::getToken([
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirecUri,
            'code' => 'dcfaf8631235878d9a9349bf138779dd8cc2df5642e4747c',
            'scope' => $this->scopes,
        ]);

        $this->assertSame($this->accessToken, $token->access_token);
        $this->assertSame('Bearer', $token->token_type);
        $this->assertInstanceOf(\DateTime::class, $token->expires_at);
        $this->assertSame($this->refreshToken, $token->refresh_token);
        $this->assertSame($this->scopes, $token->scope);
    }

    public function testRefreshValidToken()
    {
        $validToken = new AccessToken([
            'access_token' => $this->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => new \DateTime('tomorrow'),
            'refresh_token' => $this->refreshToken,
            'scope' => $this->scopes,
        ]);
        $token = OAuth::refreshToken($validToken);

        $this->assertEquals($validToken, $token);
    }

    public function testRefreshExpiredToken()
    {
        $expiredToken = new AccessToken([
            'token_type' => 'Bearer',
            'access_token' => $this->accessToken,
            'expires_at' => new \DateTime('2000-01-28T15:00:00'),
            'refresh_token' => $this->refreshToken,
            'scope' => $this->scopes,
        ]);

        $body = json_encode([
            'access_token' => 'new_token',
            'token_type' => 'Bearer',
            'expires_in' => 3600,
            'scope' => $this->scopes,
        ]);

        $this->setMockClient([
            new Response(200, ['Content-Type' => 'application/json'], $body),
        ]);

        $token = OAuth::refreshToken($expiredToken);

        $this->assertSame('new_token', $token->access_token);
        $this->assertSame('Bearer', $token->token_type);
        $this->assertInstanceOf(\DateTime::class, $token->expires_at);
        $this->assertSame($this->scopes, $token->scope);
    }

}
