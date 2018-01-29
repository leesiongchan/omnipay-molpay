<?php

namespace Omnipay\MOLPay\Message;

use Omnipay\Tests\TestCase;

class PartialRefundRequestTest extends TestCase
{
    /**
     * @var \Omnipay\MOLPay\Message\PartialRefundRequest
     */
    private $request;

    public function setUp()
    {
        $client = $this->getHttpClient();
        $request = $this->getHttpRequest();

        $this->request = new PartialRefundRequest($client, $request);
    }

    public function testGetData()
    {
        $this->request->setMerchantId('your_merchant_id');
        $this->request->setRefId('merchant_refund_ref_id');
        $this->request->setTransactionReference('25248208');
        $this->request->setChannel('FPX_MB2U');
        $this->request->setAmount('10.00');
        $this->request->setBankCode('MBBEMYKL');
        $this->request->setBeneficiaryName('beneficiary_name');
        $this->request->setBeneficiaryAccountNo('beneficiary_account_no');
        $this->request->setSecretKey('your_secret_key');

        $expected = array();
        $expected['RefundType'] = 'P';
        $expected['MerchantID'] = 'your_merchant_id';
        $expected['RefID'] = 'merchant_refund_ref_id';
        $expected['TxnID'] = '25248208';
        $expected['Channel'] = 'FPX_MB2U';
        $expected['Amount'] = '10.00';
        $expected['BankCode'] = 'MBBEMYKL';
        $expected['BeneficiaryName'] = 'beneficiary_name';
        $expected['BeneficiaryAccNo'] = 'beneficiary_account_no';
        $expected['Signature'] = 'aafbef8720b13a33b37370a1a3b1c238';
        $expected['mdr_flag'] = null;
        $expected['notify_url'] = null;

        $this->assertEquals($expected, $this->request->getData());
    }
}
