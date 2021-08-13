<?php

namespace Omnipay\MOLPay\Message;

use Omnipay\Common\Message\AbstractResponse;

class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->getRequest()->getMessage();
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionId()
    {
        return $this->getRequest()->getTransactionId();
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionReference()
    {
        return $this->getRequest()->getTransactionReference();
    }

    /**
     * {@inheritdoc}
     */
    public function isPending()
    {
        return '22' === $this->getRequest()->getStatus();
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return '00' === $this->getRequest()->getStatus();
    }
}
