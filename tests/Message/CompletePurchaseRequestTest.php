<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 6/2/18
 * Time: 10:31 AM
 */

namespace Omnipay\Payoo\Message;


use Omnipay\Tests\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    /**
     * @var CompletePurchaseRequest
     */
    public $request;

    public function setUp()
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize([
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
            'apiPassword' => 'xL0EFyakWD925t8V',
            'apiSignature' => 'hsiPxtzDUjkfb2iRHhP3JSwgw4uMTJoV16AW3aSPmP36IdeZIwU2Wgmv4AyCd1cQ',
            'secretKey' => $secretKey = '73b3f5b8efa2c3654b75bf6f5afb76d0',
            'shopId' => $shopId = '691',
            'shopTitle' => $shopTitle = 'shop_client',
            'shopDomain' => $shopDomain = 'http://localhost',
        ]);
    }

    public function testPurchaseGet()
    {
        $getQuery = [
            'session' => 'SS7981',
            'order_no' => 'ORD77823',
            'status' => '1',
            'checksum' => '60a762dc6b241bec39edb7a0484405cd803a363472e26cb5ede9af1db8ce3846463996836'
        ];

        $this->getHttpRequest()->query->replace($getQuery);

        $data = $this->request->getData();

        $expected = [
            'session' => 'SS7981',
            'order_no' => 'ORD77823',
            'status' => '1',
            'checksum' => '60a762dc6b241bec39edb7a0484405cd803a363472e26cb5ede9af1db8ce3846463996836',
            'computed_checksum' => hash('sha512',
                '73b3f5b8efa2c3654b75bf6f5afb76d0' .
                'SS7981' . '.' .
                'ORD77823' . '.' .
                '1'
            ),
            'method' => 'get'
        ];

        $this->assertSame($expected, $data);
    }

    public function testPurchasePost()
    {
        $orderNo = 'sbb-test-359';
        $state = 'PAYMENT_RECEIVED';
        $signature = '5765046C20D8479C31DEB1751715E88543B7D4B2588C0E1374679FCC32EA5375B3AC1785A4064DC8BCDC70583985240C0BF58B788B17E52D2D2DAE19BD75BE19';
        $keyFields = 'State|ShopID|OrderNo|OrderCashAmount|ShippingDays|BillingCode';

        $postData = [
            'NotifyData' => '<?xml version="1.0"?>' .
                '<PayooConnectionPackage xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">' .
                '<Data>PFBheW1lbnROb3RpZmljYXRpb24+PEJpbGxpbmdDb2RlPjg4ODUxNTk5MjwvQmlsbGluZ0NvZGU+PE9yZGVyTm8+c2JiLXRlc3QtMzU5PC9PcmRlck5vPjxPcmRlckNhc2hBbW91bnQ+MTAwMDA8L09yZGVyQ2FzaEFtb3VudD48U2hvcElkPjc0MTwvU2hvcElkPjxTdGF0ZT5QQVlNRU5UX1JFQ0VJVkVEPC9TdGF0ZT48L1BheW1lbnROb3RpZmljYXRpb24+</Data>' .
                '<Signature>' . $signature . '</Signature>' .
                '<PayooSessionID>HdXEjhpiogNDuK98J4ToxqkoKYlrOTX7uN2sC7o9b6iLF4OzeKI3HSJbwYv94SyPmTkxNoZ93OWAevCcGG0QRg==</PayooSessionID>' .
                '<KeyFields>' . $keyFields . '</KeyFields>' .
                '</PayooConnectionPackage>'
        ];

        $this->request->setApiUsername('iss_pulse_active_BizAPI');
        $this->request->setSecretKey('69c215746ce4209a2e2ede09de9cca87');
        $this->request->setShopId('741');
        $this->request->setShopTitle('pulse_active');
        $this->request->setShopDomain('http://test.sbbhosted.com');

        $this->getHttpRequest()->setMethod('post');

        $this->getHttpRequest()->request->replace($postData);

        $data = $this->request->getData();

        $expected = [
            'order_no' => $orderNo,
            'state' => $state,
            'signature' => $signature,
            'computed_checksum' => strtolower($signature),
            'method' => 'post'
        ];

        $this->assertEquals($expected, $data);
    }

    public function testSend()
    {
        $data = [
            'session' => 'SS7981',
            'order_no' => 'ORD77823',
            'status' => '1',
            'checksum' => '123',
            'computed_checksum' => '123',
            'method' => 'get'
        ];

        $result = $this->request->sendData($data);

        $this->assertInstanceOf(CompletePurchaseResponse::class, $result);
    }
}
