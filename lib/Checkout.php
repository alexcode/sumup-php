<?php

namespace Sumup;

class Checkout extends SumupObject
{
    const API_ENDPOINT = 'checkouts';

    /**
     * Merchant specific transaction ID
     * @var string|null
     */
    public $checkout_reference;
    /**
     * The amount the customer will be charged
     * @var float|null
     */
    public $amount;
    /**
     * The currency of the amount being processed
     * @var string|null
     */
    public $currency;
    /**
     * Email of the payee
     * @var string|null
     */
    public $pay_to_email;
    /**
     * Email of the payer
     * @var string|null
     */
    public $pay_from_email;
    /**
     * Description of the payment
     * @var string|null
     */
    public $description;
    /**
     * Sumup checkout id
     * @var string|null
     */
    public $id;
    /**
     * Checkout status - Possible values PENDING,PAID,FAILED
     * @var string|null
     */
    public $status;
    /**
     * Checkout creation date
     * @var string|null
     */
    public $date;
    /**
     * Url to redirect the user to after completing the payment
     * @var string|null
     */
    public $return_url;
    /**
     * Expiration time of the checkout. If it is not present, the checkout will not expire
     * @var string|null
     */
    public $valid_until;
    /**
     * List of transactions associated with this checkout
     * @var \Sumup\Transaction[]
     */
    public $transactions;

    /**
     * Required properties
     * @var array
     */
    public $required = [
        "checkout_reference",
        "amount",
        "pay_to_email"
    ];

    public function __construct(array $attributes = null)
    {
        parent::__construct($attributes);
        if (is_array($this->transactions)) {
            $createTransaction = function(array $params = null) {
                return new Transaction($params);
            };
            $this->transactions = array_map($createTransaction, $this->transactions);
        }
    }

    public static function create($params, AccessToken $token)
    {
        $response = (new ApiRequestor())->setAccessToken($token)->request(
            'post',
            self::API_ENDPOINT,
            new Checkout($params)
        );

        return new Checkout($response->toArray());
    }

    public function getCompleteUrl()
    {
        if (!property_exists($this, 'id')) {
            throw new Error\CheckoutError(
                "This checkout has not been created", 1);
        }

        return Sumup::getApiBase()
        . '/' . Sumup::getApiVersion()
        . '/' . self::API_ENDPOINT
        . '/' . $this->id;
    }
}
