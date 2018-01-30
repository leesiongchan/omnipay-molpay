# Omnipay: MOLPay

**MOLPay driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/leesiongchan/omnipay-molpay.png?branch=master)](https://travis-ci.org/leesiongchan/omnipay-molpay)
[![Latest Stable Version](https://poser.pugx.org/leesiongchan/omnipay-molpay/v/stable)](https://packagist.org/packages/leesiongchan/omnipay-molpay)
[![Total Downloads](https://poser.pugx.org/leesiongchan/omnipay-molpay/downloads)](https://packagist.org/packages/leesiongchan/omnipay-molpay)
[![Latest Unstable Version](https://poser.pugx.org/leesiongchan/omnipay-molpay/v/unstable)](https://packagist.org/packages/leesiongchan/omnipay-molpay)
[![License](https://poser.pugx.org/leesiongchan/omnipay-molpay/license)](https://packagist.org/packages/leesiongchan/omnipay-molpay)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements MOLPay support for Omnipay.

[MOLPay](http://www.molpay.com) is a payment gateway offering from MOLPay Sdn Bhd. This package follows the **MOLPay API Specification (Version 13.7: Updated on 10 Oct 2017)**.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "leesiongchan/omnipay-molpay": "~2.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:

* MOLPay (MOLPay Payment)

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Example

### Create a purchase request

The example below explains how you can create a purchase request then send it.

```php
$gateway = Omnipay::create('MOLPay');

$gateway->setCurrency('MYR');
$gateway->setEnableIPN(true); // Optional
$gateway->setLocale('en'); // Optional
$gateway->setMerchantId('test1234');
$gateway->setVerifyKey('abcdefg');

$options = [
    'amount' => '10.00',
    'card' => new CreditCard(array(
        'country' => 'MY',
        'email' => 'abc@example.com',
        'name' => 'Lee Siong Chan',
        'phone' => '0123456789',
    )),
    'description' => 'Test Payment',
    'transactionId' => '20160331082207680000',
    'paymentMethod' => 'credit', // Optional
];

$response = $gateway->purchase($options)->send();

// Get the MOLPay payment URL (https://www.onlinepayment.com.my/MOLPay/pay/...)
$redirectUrl = $response->getRedirectUrl();
```

### Complete a purchase request

When the user submit the payment form, the gateway will redirect you to the return URL that you have specified in MOLPay. The code below gives an example how to handle the server feedback answer.

```php
$response = $gateway->completePurchase($options)->send();

if ($response->isSuccessful()) {
    // Do something
    echo $response->getTransactionReference();
} elseif ($response->isPending()) {
    // Do something
} else {
    // Error
}
```

### Void or Reverse a 'captured' transaction
###### Only available for limited merchants and channels

The following is the example to void a captured transaction, your can refer to MOLPay Reversal Request api spec.

```php
$gateway = Omnipay::create('MOLPay');

$gateway->setMerchantId('your_merchant_id');
$gateway->setVerifyKey('your_verify_key');
$gateway->setSecretKey('your_secret_key');

$request = $gateway->void([
    'transactionReference' => '25248208'
]);
        
$response = $request->send();

if ($response->isSuccessful()) {
    // Update your data model
} else {
    echo $response->getMessage();
}
```

### Request Partial Refund for a 'captured' or 'settled' transaction
###### Only available for limited merchants and channels

To perform a partial refund, you need to specify more parameters as below

```php
$gateway = Omnipay::create('MOLPay');

$gateway->setMerchantId('your_merchant_id');
$gateway->setVerifyKey('your_verify_key');
$gateway->setSecretKey('your_secret_key');

$request = $gateway->refund([
    'transactionReference'  => '25248208',
    'refId'                 => 'merchant_refund_red_id',
    'amount'                => '10.00',
    'channel'               => $transaction_channel, // data saved from $gateway->purchase() response, e.g FPX_MB2U
    'bankCode'              => $bank_code, // from user who request to refund
    'beneficiaryName'       => $beneficiary_name, // from user who request to refund
    'beneficiaryAccountNo'  => $beneficiary_account_no, // from user who request to refund
]);
        
$response = $request->send();

// The refund process will take about 7-14 days after the request sent
if ($response->isSuccessful() || $response->isPending() ) {
    // Update your data model
} else {
    echo $response->getMessage();
}
```

## Out Of Scope

Omnipay does not cover recurring payments or billing agreements, and so those features are not included in this package. Extensions to this gateway are always welcome.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/leesiongchan/omnipay-molpay/issues),
or better yet, fork the library and submit a pull request.
