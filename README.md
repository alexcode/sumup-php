# SumUp PHP SDK
[![Build Status](https://travis-ci.org/alexcode/sumup-php.svg?branch=master)](https://travis-ci.org/alexcode/sumup-php)

> This is a work in progress package

## Requirements

PHP 7 and later.

## Composer

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require alexcode/sumup-php
```

## Basic Usage

Setup your credentials
```php
Sumup\Sumup::setClientSecret('MY_CLIENT_SECRET');
Sumup\Sumup::setClientId('MY_CLIENT_ID');
Sumup\Sumup::setRedirectUri('MY_OAUTH_REDIRECT');
```
### OAuth
> As a merchant, you will need to Authorize your app to make requests on your behalf with OAuth

#### Authorization Code Grant
Get the authorize Url to redirect your merchant to.

SumUp doc [Authorization Code Grant](http://docs.sumup.com/oauth/#header-authorization-code-grant)

```php
Sumup\OAuth::authorizeUrl(['scope' => 'payments']);
// https://api.sumup.com/authorize?scope=payments&client_id=MY_CLIENT_ID&redirect_uri=MY_OAUTH_REDIRECT&response_type=code
```
Upon accepting the dialog, the merchant browser will hit your redirect URI with
the code in the GET parameter (ex: http://MY_OAUTH_REDIRECT/?code=246d97b0b730c61f5929drfb3a444948fd54c058d0416019)

Therefore, you can create an Access Token to act on behalf of your Merchant.

#### Get Access Token
```php
$access_token = Sumup\OAuth::getToken([
  'grant_type' => 'authorization_code',
  'code' => '246d97b0b730c61f5929drfb3a444948fd54c058d0416019'
]);
```

#### Refresh Access Token

SumUp doc [Refresh Tokens](http://docs.sumup.com/oauth/#header-refresh-tokens)

```php
$refreshed = Sumup\OAuth::refreshToken($access_token);
```

## Checkout

### Create the checkout server-side

SumUp doc [Create checkout API](http://docs.sumup.com/rest-api/checkouts-api/#checkouts-create-checkout-post)
```php
$checkout = Sumup\Checkout::create([
  'amount' => 20,
  'currency' => 'EUR',
  'checkout_reference' => 'MY_REF',
  'pay_to_email' => 'MY_CUSTOMER_EMAIL',
]);

echo $checkout->getCompleteUrl();
// https://api.sumup.com/v0.1/checkouts/123456
```

### Complete the checkout client-side
Use the URL to complete the payment in the client browser. Therefore, no PCI data is ever hitting your server.

SumUp doc [Complete checkout API](http://docs.sumup.com/rest-api/checkouts-api/#checkouts-complete-checkout)

```bash
PUT https://api.sumup.com/v0.1/checkouts/123456

body:
{
  "payment_type": "card",
  "card": {
    "name": "...",
    "number": "...",
    "expiry_year": "...",
    "expiry_month": "...",
    "cvv": "..."
  }
}
```
Note that a checkout can be completed in a browser only from a domain that is present in your OAuth setup as an authorized javascript origin(s).

## List of currently implemented SumUp API

#### Checkouts API
-   [x] Create checkout
-   [x] Complete checkout
-   [ ] Create customer
-   [ ] Get payment instruments
-   [ ] Create payment instrument
-   [ ] Disable payment instrument

#### Transactions API
-   [ ] Transaction history
-   [ ] Transaction details
-   [x] Refund transaction
-   [ ] Receipt data

#### Accounts API
-   [ ] Get account
-   [ ] Get personal profile
-   [ ] Create personal profile
-   [ ] Get merchant profile
-   [ ] Create merchant profile
-   [ ] Get DBA
-   [ ] Edit DBA
-   [ ] Get Bank Accounts
-   [ ] Create Bank Accounts
-   [ ] Payouts Get settings
-   [ ] Payouts Edit settings
###### Accounts API Employee
-   [ ] Create Employee
-   [ ] Get Employees
-   [ ] Change employee credentials
###### Accounts API Shelf
-   [ ] Get shelves
-   [ ] Create shelf
-   [ ] Create shelf
-   [ ] Edit shelf
-   [ ] Delete shelf
###### Accounts API Product
-   [ ] Get products
-   [ ] Create product
-   [ ] Get product
-   [ ] Edit product
-   [ ] Delete product
-   [ ] Get product prices
-   [ ] Create product price
-   [ ] Get product price
-   [ ] Edit product price
-   [ ] Delete product price
###### Accounts API Application Settings
-   [ ] Application Get settings
-   [ ] Application Edit settings

## Credits
This Library is loosely inspired from [Stripe PHP](https://github.com/stripe/stripe-php)
