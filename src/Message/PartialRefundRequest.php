<?php

namespace Omnipay\MOLPay\Message;

class PartialRefundRequest extends AbstractRequest
{
    protected $endpoint = 'https://api.molpay.com/MOLPay/API/refundAPI/index.php';

    protected $sandboxEndpoint = 'https://sandbox.molpay.com/MOLPay/API/refundAPI/index.php';

    public function setRefundType($value)
    {
        return $this->setParameter('refundType', $value);
    }

    public function getRefundType()
    {
        return $this->getParameter('refundType');
    }

    public function setRefId($value)
    {
        return $this->setParameter('refId', $value);
    }

    public function getRefId()
    {
        return $this->getParameter('refId');
    }

    public function setBankCode($value)
    {
        return $this->setParameter('bankCode', $value);
    }

    public function getBankCode()
    {
        return $this->getParameter('bankCode');
    }

    public function setBeneficiaryName($value)
    {
        return $this->setParameter('beneficiaryName', $value);
    }

    public function getBeneficiaryName()
    {
        return $this->getParameter('beneficiaryName');
    }

    public function setBeneficiaryAccountNo($value)
    {
        return $this->setParameter('beneficiaryAccountNo', $value);
    }

    public function getBeneficiaryAccountNo()
    {
        return $this->getParameter('beneficiaryAccountNo');
    }

    public function setChannel($value)
    {
        return $this->setParameter('channel', $value);
    }

    public function getChannel()
    {
        return $this->getParameter('channel');
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
