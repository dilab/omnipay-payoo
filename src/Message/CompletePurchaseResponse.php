<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 5/2/18
 * Time: 10:03 AM
 */

namespace Omnipay\Payoo\Message;


use Omnipay\Common\Message\AbstractResponse;

class CompletePurchaseResponse extends AbstractResponse
{
    const STATE_SUCCESS = 'PAYMENT_RECEIVED';

    public function isSuccessful()
    {
        // TODO: Implement isSuccessful() method.
    }

}