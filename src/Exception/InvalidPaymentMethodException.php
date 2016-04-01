<?php

namespace Omnipay\MOLPay\Exception;

use Omnipay\Common\Exception\OmnipayException;

/**
 * Invalid Payment Method Exception.
 *
 * Thrown when a payment method is invalid.
 */
class InvalidPaymentMethodException extends \Exception implements OmnipayException
{
}
