<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 5/2/18
 * Time: 10:01 AM
 */

namespace Omnipay\Payoo;


use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{

    public function getName()
    {
        return 'Payoo';
    }

    public function getDefaultParameters()
    {
        return [
            'apiUsername' => '',
            'apiPassword' => '',
            'apiSignature' => '',
            'secretKey' => '',
            'shopId' => '',
            'shopTitle' => '',
            'shopDomain' => '',
        ];
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

    public function getApiPassword()
    {
        return $this->getParameter('apiPassword');
    }

    public function setApiPassword($apiPassword)
    {
        return $this->setParameter('apiPassword', $apiPassword);
    }

    public function getApiSignature()
    {
        return $this->getParameter('apiSignature');
    }

    public function setApiSignature($apiSignature)
    {
        return $this->setParameter('apiSignature', $apiSignature);
    }

    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }

    public function setSecretKey($secretKey)
    {
        return $this->setParameter('secretKey', $secretKey);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Payoo\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Payoo\Message\CompletePurchaseRequest', $parameters);
    }


}