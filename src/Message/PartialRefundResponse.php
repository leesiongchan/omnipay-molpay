<?php

namespace Omnipay\MOLPay\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Class PartialRefundResponse
 * @package Omnipay\MOLPay\Message
 *
 * MOLPay Partial Refund Response
 *
 * ### Positive Result
 * * RefundType [mandatory] - Content follow merchant request
 * * MerchantID [mandatory] - Content follow merchant request
 * * RefID      [mandatory] - Content follow merchant request
 * * RefundID   [mandatory] - Refund ID provided by MOLPay
 * * TxnID      [mandatory] - Content follow merchant request
 * * Amount     [mandatory] - Content follow merchant request
 * * Status     [mandatory] - 22 for 'Pending' , 11 for 'Rejected' and 00 for 'Success'
 * * Signature  [mandatory] - This is data integrity protection hash string
 * * reason     [optional]  - Reason for rejected status
 *
 * ### Negative Result
 * * error_code - Refer to API Spec Appendix C
 * * error_desc - Refer to API Spec Appendix C
 */
class PartialRefundResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        // Handle MOLPay returned error
        if (array_key_exists('error_desc', $this->data)) {
            return $this->data['error_desc'];
        }
        // Handle MOLPay return success with status 'Rejected'
        else if (array_key_exists('reason', $this->data)) {
            return $this->data['reason'];
        }
        // Handle MOLPay returned unknown exceptions that not specified in spec
        else {
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

        // API returned 'success', not actual '00' at this development time
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

        // API returned 'pending', not actual '22' at this development time
        return ($this->data['Status'] === '22' || strtolower($this->data['Status']) === 'pending');
    }
}
