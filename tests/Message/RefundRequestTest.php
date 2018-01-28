<?php

namespace Omnipay\MOLPay\Message;

use Omnipay\Tests\TestCase;

class RefundRequestTest extends TestCase
{
    /**
     * @var \Omnipay\MOLPay\Message\RefundRequest
     */
    private $request;

    public function setUp()
    {
        $client = $this->getHttpClient();

        $request = $this->getHttpRequest();

        $this->request = new RefundRequest($client, $request);
    }

    public function testGetData()
    {
        $this->request->setTransactionReference('25248208');
        $this->request->setMerchantId('your_merchant_id');
        $this->request->setSecretKey('your_secret_key');

        $expected = array();
        $expected['txnID'] = '25248208';
        $expected['domain'] = 'your_merchant_id';
        $expected['skey'] = 'd07b97e2b8c7234792d3fb1fe56db619';

        $this->assertEquals($expected, $this->request->getData());
    }
}
