<?php

namespace Omnipay\MOLPay\Message;

use Omnipay\Tests\TestCase;

class PartialRefundResponseTest extends TestCase
{
    public function testPartialRefundPending()
    {
        $httpResponse = $this->getMockHttpResponse('PartialRefundPending.txt');
        $response = new PartialRefundResponse($this->getMockRequest(), $httpResponse->json());

        $this->assertTrue($response->isPending());
    }

    public function testPartialRefundError()
    {
        $httpResponse = $this->getMockHttpResponse('PartialRefundError.txt');
        $response = new PartialRefundResponse($this->getMockRequest(), $httpResponse->json());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isPending());
        $this->assertSame('Exceed refund amount for this transaction.', $response->getMessage());
    }
}