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
            'sKey' => '9b8be764cc5bad1b4a5d58a3ba4daf58',
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
        $this->request->setSKey('9b8be764cc5bad1b4a5d58a3ba4daf58');

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
        $this->request->setSKey('9d65ed0b785fea1c8fc80b8316555ee3');

        $response = $this->request->send();

        $this->assertEquals('000001', $response->getTransactionReference());
        $this->assertFalse($response->isCancelled());
        $this->assertTrue($response->isPending());
        $this->assertFalse($response->isRedirect());
        $this->assertFalse($response->isSuccessful());
    }
}
