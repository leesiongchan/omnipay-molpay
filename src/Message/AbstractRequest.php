<?php

namespace Omnipay\MOLPay\Message;

use Omnipay\Common\Helper;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Omnipay\MOLPay\Exception\InvalidCreditCardDetailsException;
use Omnipay\MOLPay\Exception\InvalidPaymentMethodException;
use Omnipay\MOLPay\PaymentMethod;

abstract class AbstractRequest extends BaseAbstractRequest
{
    const API_VERSION = '13.7';

    /**
     * Endpoint URL.
     *
     * @var string
     */
    protected $endpoint = 'https://www.onlinepayment.com.my/MOLPay/pay/';

    /**
     * Sandbox Endpoint URL.
     *
     * @var string
     */
    protected $sandboxEndpoint = 'https://sandbox.molpay.com/MOLPay/pay/';

    /**
     * MOLPay IPN (Instant Payment Notification) endpoint URL.
     *
     * @var string
     */
    protected $IPNEndpoint = 'https://www.onlinepayment.com.my/MOLPay/API/chkstat/returnipn.php';

    /**
     * Get enableIPN.
     *
     * @return bool
     */
    public function getEnableIPN()
    {
        return $this->getParameter('enableIPN');
    }

    /**
     * Set enableIPN.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function setEnableIPN($value)
    {
        return $this->setParameter('enableIPN', $value);
    }

    /**
     * Get locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->getParameter('locale');
    }

    /**
     * Set locale.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setLocale($value)
    {
        return $this->setParameter('locale', $value);
    }

    /**
     * Get merchantId.
     *
     * @return string
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * Set merchantId.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * Get verifyKey.
     *
     * @return string
     */
    public function getVerifyKey()
    {
        return $this->getParameter('verifyKey');
    }

    /**
     * Set verifyKey.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setVerifyKey($value)
    {
        return $this->setParameter('verifyKey', $value);
    }

    /**
     * Get secretKey.
     *
     * @return string
     */
    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }

    /**
     * Set secretKey.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setSecretKey($value)
    {
        return $this->setParameter('secretKey', $value);
    }

    /**
     * Gets the test mode of the request from the gateway.
     *
     * @return boolean
     */
    public function getTestMode()
    {
        return $this->getParameter('testMode');
    }

    /**
     * Sets the test mode of the request.
     *
     * @param boolean $value True for test mode on.
     * @return AbstractRequest
     */
    public function setTestMode($value)
    {
        return $this->setParameter('testMode', $value);
    }

    /**
     * Get endpoint.
     *
     * @return string
     */
    public function getEndpoint()
    {
        $this->validate('merchantId');

        return ($this->getTestMode() ? $this->sandboxEndpoint : $this->endpoint).$this->getMerchantId().'/';
    }

    /**
     * Send IPN (Instant Payment Notification).
     */
    protected function sendIPN()
    {
        $data = $this->httpRequest->request->all();

        $data['treq'] = 1; // Additional parameter required by IPN

        $this->httpClient->post($this->IPNEndpoint, null, $data)->send();
    }

    /**
     * Validate credit card details:
     * - country
     * - email
     * - name
     * - phone.
     */
    protected function validateCreditCardDetails()
    {
        $this->validate('card');

        $card = $this->getCard();

        foreach (array('country', 'email', 'name', 'phone') as $key) {
            $method = 'get'.ucfirst(Helper::camelCase($key));

            if (null === $card->$method()) {
                throw new InvalidCreditCardDetailsException("The $key parameter is required");
            }
        }
    }

    /**
     * Validate payment method:
     * - Affin Bank
     * - AmOnline
     * - CIMB Clicks
     * - Credit Card
     * - FPX
     * - Hong Leong Connect
     * - Maybank2u
     * - RHB Now.
     */
    protected function validatePaymentMethod()
    {
        $this->validate('paymentMethod');

        $paymentMethod = strtolower($this->getPaymentMethod());

        if (
            PaymentMethod::AFFIN_BANK !== $paymentMethod &&
            PaymentMethod::AM_ONLINE !== $paymentMethod &&
            PaymentMethod::CIMB_CLICKS !== $paymentMethod &&
            PaymentMethod::CREDIT_CARD !== $paymentMethod &&
            PaymentMethod::FPX !== $paymentMethod &&
            PaymentMethod::HONG_LEONG_CONNECT !== $paymentMethod &&
            PaymentMethod::MAYBANK2U !== $paymentMethod &&
            PaymentMethod::RHB_NOW !== $paymentMethod
        ) {
            throw new InvalidPaymentMethodException("The payment method ($paymentMethod) is not supported");
        }
    }
}
