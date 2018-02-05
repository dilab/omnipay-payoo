<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 5/2/18
 * Time: 10:01 AM
 */

namespace Omnipay\Payoo\Message;

use Omnipay\Common\Message\ResponseInterface;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{

    protected $productionEndpoint = '';

    protected $sandboxEndpoint = '';

    public function getCurrency()
    {
        // only works for VND
        return 'VND';
    }

    protected function getEndPoint()
    {
        if ($this->getTestMode()) {
            return $this->sandboxEndpoint;
        }

        return $this->productionEndpoint;
    }

    public function getShopDomain()
    {
        return $this->getParameter('shopDomain');
    }

    public function setShopDomain($shopDomain)
    {
        return $this->setParameter('shopDomain', $shopDomain);
    }

    public function getShopTitle()
    {
        return $this->getParameter('shopTitle');
    }

    public function setShopTitle($shopTitle)
    {
        return $this->setParameter('shopTitle', $shopTitle);
    }

    public function getShopId()
    {
        return $this->getParameter('shopId');
    }

    public function setShopId($shopId)
    {
        return $this->setParameter('shopId', $shopId);
    }

    public function getApiUserName()
    {
        return $this->getParameter('apiUserName');
    }

    public function setApiUserName($apiUserName)
    {
        return $this->setParameter('apiUserName', $apiUserName);
    }

    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }

    public function setSecretKey($secretKey)
    {
        return $this->setParameter('secretKey', $secretKey);
    }
}