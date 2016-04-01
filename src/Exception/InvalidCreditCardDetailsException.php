<?php

namespace Omnipay\MOLPay\Exception;

use Omnipay\Common\Exception\OmnipayException;

/**
 * Invalid Credit Card Details Exception.
 *
 * Thrown when a credit card details is invalid or missing required fields.
 */
class InvalidCreditCardDetailsException extends \Exception implements OmnipayException
{
}
