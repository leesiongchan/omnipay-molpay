<?php

namespace Omnipay\MOLPay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Class RefundResponse
 * @package Omnipay\MOLPay\Message
 */
class RefundResponse extends AbstractResponse
{
    /**
     * Reversal Request Response statCodes and messages
     */
    protected $statCodeMessages = array(
        '00' => 'Success',
        '11' => 'Failure',
        '12' => 'Invalid or unmatched security hash string',
        '13' => 'Not a refundable transaction',
        '14' => 'Transaction date more than 45 days',
        '15' => 'Requested day is on settlement day',
        '16' => 'Forbidden transaction',
        '17' => 'Transaction not found'
    );

    /**
     * RefundResponse constructor.
     * @param RequestInterface $request
     * @param mixed $data
     */
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;

        $search = array("\r\n", "\n", "\r");
        $data = str_replace($search, '&', $data);

        // Parse string to key value mapping
        parse_str($data, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->statCodeMessages[$this->data['StatCode']];
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return '00' === $this->data['StatCode'];
    }
}
