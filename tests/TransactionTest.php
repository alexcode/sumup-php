<?php

namespace Sumup;

use GuzzleHttp\Psr7\Response;
use Sumup\Transaction;

/**
 * Base class for SumUp test cases.
 */
class TransactionTest extends TestCase
{
    public function testRefundTransactionSuccess()
    {
        $body = 'Processing completed';

        $this->setMockClient([
            new Response(204, ['Content-Type' => 'application/json'], $body),
        ]);


        $transaction = new Transaction([
            'id' => 't_id',
            'transaction_code' => 't_my_id',
            'merchant_code' => 'm_code',
            'amount' => 1,
            'currency' => 'EUR',
            'status' => 'SUCCESSFUL',
            'payment_type' => 'ECOM',
            'type' => 'PAYMENT',
        ]);

        $response = $transaction->refund($this->getMockedToken());
        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertEquals(204, $response->raw->getStatusCode());
    }

    public function testRefundTransactionAmountTypeError()
    {
        $this->expectException(Error\TransactionError::class);
        $this->expectExceptionCode(1);

        $transaction = new Transaction([
            'id' => 't_id',
            'transaction_code' => 't_my_id',
            'merchant_code' => 'm_code',
            'amount' => 1,
            'currency' => 'EUR',
            'status' => 'SUCCESSFUL',
            'payment_type' => 'ECOM',
            'type' => 'PAYMENT',
        ]);

        $response = $transaction->refund($this->getMockedToken(), 'invalid_amount');
    }

    public function testRefundTransactionAmountError()
    {
        $this->expectException(Error\TransactionError::class);
        $this->expectExceptionCode(2);

        $transaction = new Transaction([
            'id' => 't_id',
            'transaction_code' => 't_my_id',
            'merchant_code' => 'm_code',
            'amount' => 1,
            'currency' => 'EUR',
            'status' => 'SUCCESSFUL',
            'payment_type' => 'ECOM',
            'type' => 'PAYMENT',
        ]);

        $response = $transaction->refund($this->getMockedToken(), 10);
    }
}
