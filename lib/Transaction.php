<?php

namespace Sumup;

class Transaction
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
}
