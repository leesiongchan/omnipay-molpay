<?php

namespace League\Omnipay\MOLPay\Message;

use League\Omnipay\Common\Message\AbstractResponse;
use League\Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRedirectData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUrl()
    {
        return $this->getRequest()->getEndpoint().'?'.http_build_query($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return false;
    }
}
