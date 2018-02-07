<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 7/2/18
 * Time: 9:18 AM
 */

namespace Omnipay\Payoo\Message;


use Omnipay\Tests\TestCase;

class CompletePurchaseResponseTest extends TestCase
{

    /**
     * @var  CompletePurchaseResponse
     */
    private $response;

    public function testResponseGetSuccess()
    {
        $data = [
            'session' => 'SS7981',
            'order_no' => 'ORD77823',
            'status' => '1',
            'checksum' => '123',
            'computed_checksum' => '123',
            'method' => 'get'
        ];

        $this->response = new CompletePurchaseResponse($this->getMockRequest(), $data);

        $this->assertTrue($this->response->isSuccessful());
        $this->assertFalse($this->response->isPending());
        $this->assertFalse($this->response->isRedirect());
        $this->assertFalse($this->response->isTransparentRedirect());
        $this->assertFalse($this->response->isCancelled());
        $this->assertEmpty($this->response->getTransactionReference());
        $this->assertEquals('ORD77823', $this->response->getTransactionId());
    }

    public function testResponseGetCancel()
    {
        $data = [
            'session' => 'SS7981',
            'order_no' => 'ORD77823',
            'status' => '-1',
            'checksum' => '123',
            'computed_checksum' => '123',
            'method' => 'get'
        ];

        $this->response = new CompletePurchaseResponse($this->getMockRequest(), $data);

        $this->assertTrue($this->response->isCancelled());
        $this->assertFalse($this->response->isSuccessful());
        $this->assertFalse($this->response->isPending());
        $this->assertFalse($this->response->isRedirect());
        $this->assertFalse($this->response->isTransparentRedirect());
    }

    public function getFailProvider()
    {
        return [
            [0, '123', '123'],
            ['0', '123', '123'],
            [1, '123', '567'],
        ];
    }

    /**
     * @dataProvider getFailProvider
     */
    public function testResponseGetFail($status, $checksum, $computedChecksum)
    {
        $data = [
            'session' => 'SS7981',
            'order_no' => 'ORD77823',
            'status' => $status,
            'checksum' => $checksum,
            'computed_checksum' => $computedChecksum,
            'method' => 'get'
        ];

        $this->response = new CompletePurchaseResponse($this->getMockRequest(), $data);

        $this->assertFalse($this->response->isSuccessful());
        $this->assertFalse($this->response->isPending());
        $this->assertFalse($this->response->isRedirect());
        $this->assertFalse($this->response->isTransparentRedirect());
        $this->assertFalse($this->response->isCancelled());
    }

    public function testResponsePostSuccess()
    {
        $data = [
            'state' => 'PAYMENT_RECEIVED',
            'signature' => '123',
            'computed_checksum' => '123',
            'method' => 'post'
        ];

        $this->response = new CompletePurchaseResponse($this->getMockRequest(), $data);

        $this->assertTrue($this->response->isSuccessful());
        $this->assertFalse($this->response->isPending());
        $this->assertFalse($this->response->isRedirect());
        $this->assertFalse($this->response->isTransparentRedirect());
        $this->assertFalse($this->response->isCancelled());
        $this->assertEmpty($this->response->getTransactionReference());
        $this->assertEquals('ORD77823', $this->response->getTransactionId());
    }

    public function testResponsePostFail()
    {

    }
}
