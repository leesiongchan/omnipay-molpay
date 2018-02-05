<?php

namespace Omnipay\MOLPay\Message;

use Omnipay\Tests\TestCase;

class ReversalResponseTest extends TestCase
{
    public function testConstruct()
    {
        $response = new ReversalResponse(
            $this->getMockRequest(),
            "TxnID=25248203\nDomain=your_merchant_id\nStatDate=2018-01-28 15:53:19\nStatCode=00\nVrfKey=f56d5ea9932861454b7fd69851f57f7c");

        $this->assertEquals(array(
            'TxnID' => '25248203',
            'Domain' => 'your_merchant_id',
            'StatDate' => '2018-01-28 15:53:19',
            'StatCode' => '00',
            'VrfKey' => 'f56d5ea9932861454b7fd69851f57f7c'),
            $response->getData());
    }

    public function testReversalSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('ReversalSuccess.txt');
        $response = new ReversalResponse($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('Success', $response->getMessage());
    }

    public function testRefundFailure()
    {
        $httpResponse = $this->getMockHttpResponse('ReversalFailure.txt');
        $response = new ReversalResponse($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Forbidden transaction', $response->getMessage());
    }
}
