<?php

namespace Omnipay\MOLPay\Message;

use Omnipay\Common\Message\AbstractResponse;

class PartialRefundResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        if(array_key_exists('error_desc', $this->data)) {
            return $this->data['error_desc'];
        } else if (array_key_exists('reason', $this->data)) {
            return $this->data['reason'];
        } else {
            return 'Unknown error';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        if (array_key_exists('error_code', $this->data)) {
            return false;
        }

        return ($this->data['Status'] === '00' || strtolower($this->data['Status']) === 'success');
    }

    /**
     * {@inheritdoc}
     */
    public function isPending()
    {
        if (array_key_exists('error_code', $this->data)) {
            return false;
        }

        return ($this->data['Status'] === '22' || strtolower($this->data['Status']) === 'pending');
    }
}
