<?php

namespace Omnipay\MOLPay;

/**
 * Payment methods accepted by MOLPay.
 */
class PaymentMethod
{
    static function supported()
    {
        return [
            'affin-­epg',
            'amb',
            'CIMBCLICKS',
            'credit',
            'fpx',
            'HLBConnect',
            'BIMB',
            'MB2U',
            'GrabPay',
            'ShopeePay',
            'cash',
            'rhb'
        ];
    }
}
