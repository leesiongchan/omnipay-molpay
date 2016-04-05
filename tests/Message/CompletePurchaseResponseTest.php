<?php

namespace League\Omnipay\MOLPay\Message;

use League\Omnipay\Tests\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    public function setUp()
    {
        $this->response = new CompletePurchaseResponse($this->getMockRequest(), array());
    }

    public function testCompletePurchaseSuccess()
    {
        $this->getMockRequest()->shouldReceive('getStatus')->andReturn('00');
        $this->getMockRequest()->shouldReceive('getTransactionId')->andReturn('20160331082207680000');
        $this->getMockRequest()->shouldReceive('getTransactionReference')->andReturn('000001');

        $this->assertTrue($this->response->isSuccessful());
        $this->assertEquals('20160331082207680000', $this->response->getTransactionId());
        $this->assertEquals('000001', $this->response->getTransactionReference());
    }

    public function testCompletePurchasePending()
    {
        $this->getMockRequest()->shouldReceive('getStatus')->andReturn('22');

        $this->assertTrue($this->response->isPending());
    }

    public function testCompletePurchaseCallbackNotification()
    {
        $this->getMockRequest()->shouldReceive('getNBCB')->andReturn('1');

        $this->assertTrue($this->response->isCallbackNotification());
    }
}
