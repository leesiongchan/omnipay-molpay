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
     * Check if the request is a callback notification.
     *
     * @return bool
     */
    public function isCallbackNotification()
    {
        return '1' === $this->getRequest()->getNBCB();
    }

    /**
     * {@inheritdoc}
     */
    public function isCancelled()
    {
        return '11' === $this->getRequest()->getStatus();
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
