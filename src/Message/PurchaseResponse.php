<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 5/2/18
 * Time: 10:02 AM
 */

namespace Omnipay\Payoo\Message;


use Omnipay\Common\Message\AbstractResponse;

class PurchaseResponse extends AbstractResponse
{
    private $endPointProduction = 'https://www.payoo.vn/v2/paynow/';
    private $endPointSandbox = 'https://newsandbox.payoo.com.vn/v2/paynow/';

    public function isSuccessful()
    {
        return false;
    }

    public function isPending()
    {
        return true;
    }

    public function isRedirect()
    {
        return true;
    }

    public function isTransparentRedirect()
    {
        return true;
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectUrl()
    {
        if ($this->request->getTestMode()) {
            return $this->endPointSandbox;
        }

        return $this->endPointProduction;
    }

    public function getRedirectData()
    {
        return $this->data;
    }

}