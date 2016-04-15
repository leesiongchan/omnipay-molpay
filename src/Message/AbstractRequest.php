<?php

namespace League\Omnipay\MOLPay\Message;

use League\Omnipay\Common\Helper;
use League\Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use League\Omnipay\MOLPay\Exception\InvalidCreditCardDetailsException;
use League\Omnipay\MOLPay\Exception\InvalidPaymentMethodException;
use League\Omnipay\MOLPay\PaymentMethod;

abstract class AbstractRequest extends BaseAbstractRequest
{
    const API_VERSION = '12.1';

    /**
     * Endpoint URL.
     *
     * @var string
     */
    protected $endpoint = 'https://www.onlinepayment.com.my/MOLPay/pay/';

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
     * Get endpoint.
     *
     * @return string
     */
    public function getEndpoint()
    {
        $this->validate('merchantId');

        return $this->endpoint.$this->getMerchantId().'/';
    }

    /**
     * Send IPN (Instant Payment Notification).
     */
    protected function sendIPN()
    {
        $data = $this->httpRequest->getQueryParams();

        $data['treq'] = 1; // Additional parameter required by IPN

        $this->httpClient->request('POST', $this->IPNEndpoint, array(), http_build_query($data));
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

        $customer = $this->getCard()->getCustomer();

        if (null === $customer) {
            throw new InvalidCreditCardDetailsException('Customer is required');
        }

        foreach (array('country', 'email', 'name', 'phone') as $key) {
            $method = 'get'.ucfirst(Helper::camelCase($key));

            if (null === $customer->$method()) {
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

        if (PaymentMethod::AFFIN_BANK !== $paymentMethod &&
            PaymentMethod::AM_ONLINE !== $paymentMethod &&
            PaymentMethod::CIMB_CLICKS !== $paymentMethod &&
            PaymentMethod::CREDIT_CARD !== $paymentMethod &&
            PaymentMethod::FPX !== $paymentMethod &&
            PaymentMethod::HONG_LEONG_CONNECT !== $paymentMethod &&
            PaymentMethod::MAYBANK2U !== $paymentMethod &&
            PaymentMethod::RHB_NOW !== $paymentMethod) {
            throw new InvalidPaymentMethodException("The payment method ($paymentMethod) is not supported");
        }
    }
}
