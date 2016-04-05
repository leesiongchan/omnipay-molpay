<?php

namespace Omnipay\MOLPay\Message;

use Omnipay\Tests\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize(array(
            'amount' => '10.00',
            'appCode' => 'abcdefg',
            'currency' => 'MYR',
            'domain' => 'test4321',
            'payDate' => '2016-03-29 04:02:21',
            'sKey' => '0c1e98d6d58f9a30b919bcaeb9790852',
            'status' => '00',
            'transactionId' => '20160331082207680000',
            'transactionReference' => '000001',
            'verifyKey' => 'abcdefg',
        ));
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertEquals('00', $data['status']);
        $this->assertEquals('000001', $data['transactionReference']);
    }

    public function testSendSuccess()
    {
        $this->request->setStatus('00');
        $this->request->setSKey('0c1e98d6d58f9a30b919bcaeb9790852');

        $response = $this->request->send();

        $this->assertEquals('000001', $response->getTransactionReference());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isPending());
        $this->assertFalse($response->isRedirect());
        $this->assertTrue($response->isSuccessful());
    }

    public function testSendPending()
    {
        $this->request->setStatus('22');
        $this->request->setSKey('8192eb5627012f236fc36e73f5d49a5e');

        $response = $this->request->send();

        $this->assertEquals('000001', $response->getTransactionReference());
        $this->assertFalse($response->isCancelled());
        $this->assertTrue($response->isPending());
        $this->assertFalse($response->isRedirect());
        $this->assertFalse($response->isSuccessful());
    }
}
