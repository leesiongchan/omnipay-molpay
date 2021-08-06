<?php

namespace Omnipay\MOLPay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

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
        return $this->getRequest()->getHttpMethod();
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUrl()
    {
        if ($this->getRedirectMethod()  == "GET") {
            return $this->getRequest()->getEndpoint() . '?' . http_build_query($this->data);
        }
        return $this->getRequest()->getEndpoint();
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
