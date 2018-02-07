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


//class GatewayTest extends GatewayTestCase
class GatewayTest extends TestCase
{
    /** @var Gateway */
    protected $gateway;

    /** @var array */
    private $options;

    public function setUp()
    {
//        parent::setUp();
//
//        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
//
//        $this->gateway->setMerchantKey('apple');
//
//        $this->gateway->setMerchantCode('M00003');
//
//        $this->gateway->setBackendUrl('https://www.example.com/backend');
//
//        $this->options = [
//            'card' => [
//                'firstName' => 'Xu',
//                'lastName' => 'Ding',
//                'email' => 'xuding@spacebib.com',
//                'number' => '93804194'
//            ],
//            'amount' => '1.00',
//            'currency' => 'MYR',
//            'description' => 'Marina Run 2016',
//            'transactionId' => '12345',
//            'returnUrl' => 'https://www.example.com/return',
//        ];
    }

    public function testPurchase()
    {
//        $response = $this->gateway->purchase($this->options)->send();
//
//        $this->assertFalse($response->isSuccessful());
//        $this->assertTrue($response->isRedirect());
    }

    public function testCompletePurchase()
    {
//        $this->getHttpRequest()->request->replace([
//            'MerchantCode' => 'M00003',
//            'PaymentId' => 2,
//            'RefNo' => '12345',
//            'Amount' => '1.00',
//            'Currency' => 'MYR',
//            'Remark' => '100',
//            'TransId' => '54321',
//            'AuthCode' => '',
//            'Status' => 1,
//            'ErrDesc' => '',
//            'Signature' => 'a4THdPHQG9jT3DPZZ/mabkXUqow='
//        ]);
//
//        $this->setMockHttpResponse('CompletePurchaseRequestReQuerySuccess.txt');
//
//        $response = $this->gateway->completePurchase($this->options)->send();
//
//        $this->assertTrue($response->isSuccessful());
//        $this->assertSame('54321', $response->getTransactionReference());
    }
}
