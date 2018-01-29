<?php

namespace Omnipay\MOLPay\Message;

/**
 * Class PartialRefundRequest
 * @package Omnipay\MOLPay\Message
 *
 * MOLPay Partial Refund
 *
 * ### Parameters
 *
 * * RefundType         [mandatory]     -   P for Partial Refund
 * * MerchantID         [mandatory]     -   Merchant ID provided by MOLPay
 * * RefID              [mandatory]     -   Unique tracking/references ID from merchant
 * * TxnID              [mandatory]     -   MOLPay Transaction ID
 * * Channel            [mandatory]     -   Refer to Channel List
 * * Amount             [mandatory]     -   eg. '5.00' Amount to be refund
 * * BankCode           [conditional]   -   Applicable for Online Banking and Physical Payment transaction only
 * * BeneficiaryName    [conditional]   -   Applicable for Online Banking and Physical Payment transaction only
 * * BeneficiaryAccNo   [conditional]   -   Applicable for Online Banking and Physical Payment transaction only
 * * Signature          [mandatory]     -   This is data integrity protection hash string
 * * mdr_flag           [optional]      -   This is to include or exclude MDR refund to buyer if the amount is same as bill amount
 *                                          Available value is as below:
 *                                          0 - Include MDR/Full Refund (Default)
 *                                          1 - Exclude/Reserved MDR
 * * notify_url         [optional]      -   This is the URL for merchant to receive refund status
 *
 */
class PartialRefundRequest extends AbstractRequest
{
    /**
     * Partial Refund URL
     *
     * @var string
     */
    protected $endpoint = 'https://api.molpay.com/MOLPay/API/refundAPI/index.php';

    /**
     * Sandbox Partial Refund URL
     *
     * @var string
     */
    protected $sandboxEndpoint = 'https://sandbox.molpay.com/MOLPay/API/refundAPI/index.php';

    /**
     * @return string
     */
    public function getRefundType()
    {
        return 'P';
    }

    /**
     * @param $value
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setRefId($value)
    {
        return $this->setParameter('refId', $value);
    }

    /**
     * @return mixed
     */
    public function getRefId()
    {
        return $this->getParameter('refId');
    }

    /**
     * @param $value
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setChannel($value)
    {
        return $this->setParameter('channel', $value);
    }

    /**
     * @return mixed
     */
    public function getChannel()
    {
        return $this->getParameter('channel');
    }

    /**
     * @param $value
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setBankCode($value)
    {
        return $this->setParameter('bankCode', $value);
    }

    /**
     * @return mixed
     */
    public function getBankCode()
    {
        return $this->getParameter('bankCode');
    }

    /**
     * @param $value
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setBeneficiaryName($value)
    {
        return $this->setParameter('beneficiaryName', $value);
    }

    /**
     * @return mixed
     */
    public function getBeneficiaryName()
    {
        return $this->getParameter('beneficiaryName');
    }

    /**
     * @param $value
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setBeneficiaryAccountNo($value)
    {
        return $this->setParameter('beneficiaryAccountNo', $value);
    }

    /**
     * @return mixed
     */
    public function getBeneficiaryAccountNo()
    {
        return $this->getParameter('beneficiaryAccountNo');
    }

    /**
     * @param $value
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setMdrFlag($value)
    {
        return $this->setParameter('mdrFlag', $value);
    }

    /**
     * @return mixed
     */
    public function getMdrFlag()
    {
        return $this->getParameter('mdrFlag');
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $this->validate('merchantId', 'refId', 'transactionReference', 'amount');

        $data = array();
        $data['RefundType'] = $this->getRefundType();
        $data['MerchantID'] = $this->getMerchantId();
        $data['RefID'] = $this->getRefId();
        $data['TxnID'] = $this->getTransactionReference();
        $data['Channel'] = $this->getChannel();
        $data['Amount'] = $this->getAmount();
        $data['BankCode'] = $this->getBankCode();
        $data['BeneficiaryName'] = $this->getBeneficiaryName();
        $data['BeneficiaryAccNo'] = $this->getBeneficiaryAccountNo();
        $data['Signature'] = $this->generateSignature();
        $data['mdr_flag'] = $this->getMdrFlag();
        $data['notify_url'] = $this->getNotifyUrl();

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

        return $this->response = new PartialRefundResponse($this, $httpResponse->json());
    }

    /**
     * Generate Signature
     * @return string
     */
    protected function generateSignature()
    {
        $this->validate('merchantId', 'refId', 'transactionReference', 'amount', 'secretKey');

        return md5($this->getRefundType() . $this->getMerchantId() .$this->getRefId() . $this->getTransactionReference() . $this->getAmount() . $this->getSecretKey());
    }
}
