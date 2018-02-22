<?php

namespace Sumup;

use GuzzleHttp\Psr7\Response;
use Sumup\Checkout;

/**
 * Base class for SumUp test cases.
 */
class CheckoutTest extends TestCase
{
    public function testCreateCheckout()
    {
        $ref = '123abc';
        $amount = 10;
        $currency = 'EUR';
        $email = 'merchant@example.com';
        $date = (new \DateTime)->format(\DateTime::ATOM);

        $body = json_encode([
            'checkout_reference' => $ref,
            'amount' => $amount,
            'currency' => $currency,
            'pay_to_email' => $email,
            'status' => 'PENDING',
            'date' => $date,
            'id' => '1234355',
        ]);

        $this->setMockClient([
            new Response(200, [
                'Content-Type' => 'application/json',
                'Date' => [
                    (new \DateTime)->format(\DateTime::ATOM)
                ]
            ], $body),
        ]);

        $params = [
            'checkout_reference' => $ref,
            'amount' => $amount,
            'currency' => $currency,
            'pay_to_email' => $email,
        ];

        $checkout = Checkout::create($params, $this->getMockedToken());
        $this->assertInstanceOf(Checkout::class, $checkout);
        $this->assertSame($ref, $checkout->checkout_reference);
        $this->assertSame($amount, $checkout->amount);
        $this->assertSame($currency, $checkout->currency);
        $this->assertSame($email, $checkout->pay_to_email);
        $this->assertSame($date, $checkout->date);
        $this->assertSame('1234355', $checkout->id);

        $uri = parse_url($checkout->getCompleteUrl());
        $this->assertSame('https', $uri['scheme']);
        $this->assertSame('localhost', $uri['host']);
        $this->assertSame('/v0.1/checkouts/1234355', $uri['path']);
    }
}
