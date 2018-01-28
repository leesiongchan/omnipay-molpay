<?php

namespace Omnipay\MOLPay\Message;


/**
 * Class RefundRequest
 * @package Omnipay\MOLPay\Message
 *
 * MOLPay Reversal Request
 * * ### Parameters
 *
 * * txnID  [required] - Unique transaction ID for tracking purpose
 * * domain [required] - Merchant ID in MOLPay system
 * * skey   [required] - This is the data integrity protection hash string
 */
class RefundRequest extends AbstractRequest
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

        return $this->response = new RefundResponse($this, $httpResponse->getBody());
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
