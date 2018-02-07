<?php

namespace Omnipay\Payoo;

use Omnipay\Tests\GatewayTestCase;
use Omnipay\Tests\TestCase;

/**
 * Created by PhpStorm.
 * User: xu
 * Date: 7/2/18
 * Time: 11:12 AM
 */


class GatewayTest extends GatewayTestCase
{
    /** @var Gateway */
    protected $gateway;

    /** @var array */
    private $options;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = [
            'card' => [
                'firstName' => $firstName = 'Xu',
                'lastName' => $lastName = 'Ding',
                'email' => $email = 'xuding@spacebib.com',
                'number' => $phone = '93804194'
            ],
            'description' => $description = '<p>Thank you for purchasing Marina Run 2018 at StarPodium</p>',
            'amount' => $amount = '10000.00',
            'returnUrl' => $returnUrl = 'http://localhost:8080/return.php',
            'notifyUrl' => $notifyUrl = 'http://localhost:8080/notify.php',
            'transactionId' => $orderNo = 'ORDER-101',
            'apiUsername' => $username = 'iss_shop_client_BizAPI',
            'secretKey' => $secretKey = '73b3f5b8efa2c3654b75bf6f5afb76d0',
            'shopId' => $shopId = '691',
            'shopTitle' => $shopTitle = 'shop_client',
            'shopDomain' => $shopDomain = 'http://localhost',
        ];
    }

    public function testPurchase()
    {
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());

        $this->assertTrue($response->isRedirect());
    }

    public function testCompletePurchase()
    {
        $this->getHttpRequest()->query->replace([
            'session' => 'SS7981',
            'order_no' => 'ORD77823',
            'status' => '1',
            'checksum' => hash('sha512',
                '73b3f5b8efa2c3654b75bf6f5afb76d0' .
                'SS7981' . '.' .
                'ORD77823' . '.' .
                '1'
            )
        ]);


        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());

        $this->assertNull($response->getTransactionReference());
    }
}
