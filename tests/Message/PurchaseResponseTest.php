<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 6/2/18
 * Time: 9:39 AM
 */

namespace Omnipay\Payoo\Message;


use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{
    /**
     * @var PurchaseResponse
     */
    private $response;

    public function testPurchase()
    {
        $request = $this->getMockRequest();

        $request->shouldReceive('getTestMode')->once()->andReturn(true);

        $this->response = new PurchaseResponse($request, $data = [
            'bc' => '',
            'pm' => '',
            'OrdersForPayoo' => 'xml',
            'CheckSum' => 'checksum123',
        ]);

        $this->assertFalse($this->response->isSuccessful());
        $this->assertTrue($this->response->isPending());
        $this->assertTrue($this->response->isRedirect());
        $this->assertTrue($this->response->isTransparentRedirect());
        $this->assertEquals('POST', $this->response->getRedirectMethod());
        $this->assertEquals('https://newsandbox.payoo.com.vn/v2/paynow/', $this->response->getRedirectUrl());
        $this->assertEquals($data, $this->response->getRedirectData());
    }

}
