<?php

namespace Omnipay\MOLPay;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    /**
     * Get the name of the gateway.
     *
     * @return string
     */
    public function getName()
    {
        return 'MOLPay';
    }

    /**
     * Get the gateway parameters.
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'enableIPN' => false,
            'locale' => 'en',
            'testMode' => false,
        );
    }

    /**
     * Get enableIPN.
     *
     * @return bool
     */
    public function getEnableIPN()
    {
        return $this->getParameter('enableIPN');
    }

    /**
     * Set enableIPN.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function setEnableIPN($value)
    {
        return $this->setParameter('enableIPN', $value);
    }

    /**
     * Get the locale.
     *
     * The default language is English.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->getParameter('locale');
    }

    /**
     * Set the locale.
     *
     * The default language is English.
     *
     * @param string $value
     * 
     * @return $this
     */
    public function setLocale($value)
    {
        return $this->setParameter('locale', $value);
    }

    /**
     * Get merchantId.
     *
     * @return string
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * Set merchantId.
     *
     * @param string $value
     * 
     * @return $this
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * Get verifyKey.
     *
     * @return string
     */
    public function getVerifyKey()
    {
        return $this->getParameter('verifyKey');
    }

    /**
     * Set verifyKey.
     *
     * @param string $value
     * 
     * @return $this
     */
    public function setVerifyKey($value)
    {
        return $this->setParameter('verifyKey', $value);
    }

    /**
     * Create a purchase request.
     *
     * @param array $parameters
     *
     * @return \Omnipay\MOLPay\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\MOLPay\Message\PurchaseRequest', $parameters);
    }

    /**
     * Complete a purchase request.
     *
     * @param array $parameters
     *
     * @return \Omnipay\MOLPay\Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\MOLPay\Message\CompletePurchaseRequest',
            array_merge(
                $parameters,
                array(
                    'appCode' => $this->httpRequest->request->get('appcode'),
                    'domain' => $this->httpRequest->request->get('domain'),
                    'errorMessage' => strlen($this->httpRequest->request->get('error_desc')) > 0 ? $this->httpRequest->request->get('error_desc') : null,
                    'payDate' => $this->httpRequest->request->get('paydate'),
                    'sKey' => $this->httpRequest->request->get('skey'),
                    'status' => $this->httpRequest->request->get('status'),
                    'transactionReference' => $this->httpRequest->request->get('tranID'),
                )
            )
        );
    }
}
