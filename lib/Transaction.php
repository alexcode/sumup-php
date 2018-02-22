<?php

namespace Sumup;

class Transaction extends SumupObject
{
    /**
     * Transaction id
     * @var string|null
     */
    public $id;
    /**
     * Transaction code
     * @var string|null
     */
    public $transaction_code;
    /**
     * Merchant code
     * @var string|null
     */
    public $merchant_code;
    /**
     * Transaction amount
     * @var float|null
     */
    public $amount;
    /**
     * Transaction VAT amount
     * @var float|null
     */
    public $vat_amount;
    /**
     * Tip amount &#40;included in transaction amount&#41;
     * @var float|null
     */
    public $tip_amount;
    /**
     * Transaction currency
     * @var string|null
     */
    public $currency;
    /**
     * Time created
     * @var string|null
     */
    public $timestamp;
    /**
     * Transaction processing status
     * @var string|null
     */
    public $status;
    /**
     * Transaction type
     * @var string|null
     */
    public $payment_type;
    /**
     * Transaction entry mode
     * @var string|null
     */
    public $entry_mode;
    /**
     * Number of installments
     * @var float|null
     */
    public $installments_count;
    /**
     * Authorization code
     * @var string|null
     */
    public $auth_code;
    /**
     * The internal transaction ID
     * @var float|null
     */
    public $internal_id;

    public function refund(AccessToken $token, $amount = null)
    {
        if (!is_null($amount) && !is_numeric($amount)) {
            $msg = sprintf(
                'The property amount %s is not a numeric value', $amount);
            throw new Error\TransactionError($msg, 1);
        }

        if (floatval($amount) > floatval($this->amount)) {
            $msg = sprintf(
                'The refunded amount %s cannot be higher than the transaction '
                .'amount %s',
                (string) $amount,
                (string) $this->amount
            );
            throw new Error\TransactionError($msg, 2);
        }

        return (new ApiRequestor())->setAccessToken($token)->request(
            'post',
            'me/refund/' . $this->id,
            [
                'amount' => $amount ?: $this->amount,
            ]
        );
    }
}
