<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 5/2/18
 * Time: 10:03 AM
 */

namespace Omnipay\Payoo\Message;



class CompletePurchaseRequest extends AbstractRequest
{

    public function getData()
    {

        if ($this->httpRequest->isMethod('get')) {
            $data = $this->httpRequest->query->all();
            $data['computed_checksum'] = hash('sha512',
                $this->getSecretKey() .
                $this->httpRequest->query->get('session') . '.' .
                $this->httpRequest->query->get('order_no') . '.' .
                $this->httpRequest->query->get('status')
            );
            $data['method'] = 'get';
            return $data;
        }

//        $data['method'] = 'post';
//        $data['computed_checksum'] = '';

    }

    public function sendData($data)
    {
    }

}