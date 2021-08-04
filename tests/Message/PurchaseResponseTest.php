<?php

namespace Omnipay\MOLPay\Message;

use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $response = new PurchaseResponse($this->getMockRequest(), array(
            'amount' => 1000,
        ));

        $this->getMockRequest()->shouldReceive('getEndpoint')->once()->andReturn('https://www.onlinepayment.com.my/MOLPay/pay/');

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertEquals('https://www.onlinepayment.com.my/MOLPay/pay/?amount=1000', $response->getRedirectUrl());
        $this->assertEquals('GET', $response->getRedirectMethod());
        $this->assertNull($response->getRedirectData());
    }
}
