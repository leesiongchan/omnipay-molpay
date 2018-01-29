<?php

namespace Omnipay\MOLPay\Message;

/**
 * Class ReversalRequest
 * @package Omnipay\MOLPay\Message
 *
 * MOLPay Reversal Request
 * ### Parameters
 *
 * * txnID  [mandatory] - Unique transaction ID for tracking purpose
 * * domain [mandatory] - Merchant ID in MOLPay system
 * * skey   [mandatory] - This is the data integrity protection hash string
 * * url    [optional]  - The URL to receive POST response from MOLPay
 * * type   [optional]  - 0 = plain text result (default)
 * *                      1 = result via POST method
 */
class ReversalRequest extends AbstractRequest
{
    /**
     * Reversal Request URL
     *
     * @var string
     */
    protected $endpoint = 'https://api.molpay.com/MOLPay/API/refundAPI/refundAPI/refund.php';

    /**
     * Sandbox Reversal Request URL
     *
     * @var string
     */
    protected $sandboxEndpoint = 'https://sandbox.molpay.com/MOLPay/API/refundAPI/refund.php';

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $this->validate('transactionReference', 'merchantId');

        $data = array();
        $data['txnID'] = $this->getTransactionReference();
        $data['domain'] = $this->getMerchantId();
        $data['skey'] = $this->generateSKey();

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->sandboxEndpoint : $this->endpoint;
    }

    /**
     * {@inheritdoc}
     */
    public function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {
        $httpRequest = $this->httpClient->createRequest(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            null,
            $data
        );

        $httpResponse = $httpRequest->send();

        return $this->response = new ReversalResponse($this, $httpResponse->getBody());
    }

    /**
     * Generate SKey
     * @return string
     */
    protected function generateSKey()
    {
        $this->validate('transactionReference', 'merchantId', 'secretKey');

        return md5($this->getTransactionReference() . $this->getMerchantId() . $this->getSecretKey());
    }
}
