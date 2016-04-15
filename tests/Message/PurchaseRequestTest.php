<?php

namespace Omnipay\MOLPay\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize(array(
            'amount' => '10.00',
            'card' => new CreditCard(array(
                'country' => 'MY',
                'email' => 'abc@example.com',
                'name' => 'Lee Siong Chan',
                'phone' => '0123456789',
            )),
            'currency' => 'MYR',
            'description' => 'Test Payment',
            'locale' => 'en',
            'merchantId' => 'test1234',
            'paymentMethod' => 'credit',
            'transactionId' => '20160331082207680000',
            'verifyKey' => 'abcdefg',
        ));
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertEquals('10.00', $data['amount']);
        $this->assertEquals('MY', $data['country']);
        $this->assertEquals('abc@example.com', $data['bill_email']);
        $this->assertEquals('Lee Siong Chan', $data['bill_name']);
        $this->assertEquals('0123456789', $data['bill_mobile']);
        $this->assertEquals('MYR', $data['currency']);
        $this->assertEquals('Test Payment', $data['bill_desc']);
        $this->assertEquals('en', $data['langcode']);
        $this->assertEquals('credit', $data['channel']);
        $this->assertEquals('20160331082207680000', $data['orderid']);
        $this->assertEquals('f3d5496b444ae3d11e09fa92a753ac60', $data['vcode']);
    }

    public function testSendSuccess()
    {
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertEquals(
            'https://www.onlinepayment.com.my/MOLPay/pay/test1234/?amount=10.00&bill_desc=Test+Payment&bill_email=abc%40example.com&bill_mobile=0123456789&bill_name=Lee+Siong+Chan&channel=credit&country=MY&currency=MYR&langcode=en&orderid=20160331082207680000&vcode=f3d5496b444ae3d11e09fa92a753ac60',
            $response->getRedirectUrl()
        );
    }
}
