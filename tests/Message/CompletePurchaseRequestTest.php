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

    public function testPurchasePost_with_billing()
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

    public function testPurchasePost_without_billing()
    {

        $orderNo = 'sbb-test-367';
        $state = 'PAYMENT_RECEIVED';
        $signature = '57B7AF83FF7D5F3AE305A28C467B900871A64DE7C9788F25B0B484944074333F47F191FBFD7084983A0128C40BCB856C9A2A5C329F3A7A154DA5055FD0D32D53';
        $keyFields = 'PaymentMethod|State|Session|BusinessUsername|ShopID|ShopTitle|ShopDomain|ShopBackUrl|OrderNo|OrderCashAmount|StartShippingDate|ShippingDays|OrderDescription|NotifyUrl|PaymentExpireDate';

        $postData = [
            'NotifyData' => '<?xml version="1.0"?>' .
                '<PayooConnectionPackage xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">' .
                '<Data>PFBheW1lbnROb3RpZmljYXRpb24+PHNob3BzPjxzaG9wPjxzZXNzaW9uPnNiYi10ZXN0LTM2Nzwvc2Vzc2lvbj48dXNlcm5hbWU+aXNzX3B1bHNlX2FjdGl2ZTwvdXNlcm5hbWU+PHNob3BfaWQ+NzQxPC9zaG9wX2lkPjxzaG9wX3RpdGxlPnB1bHNlX2FjdGl2ZTwvc2hvcF90aXRsZT48c2hvcF9kb21haW4+aHR0cDovL3Rlc3Quc2JiaG9zdGVkLmNvbTwvc2hvcF9kb21haW4+PHNob3BfYmFja191cmw+aHR0cDovL3Rlc3Quc2JiaG9zdGVkLmNvbS9wYXltZW50cy9jb21wbGV0ZV9wdXJjaGFzZS8zNjcvcGF5b288L3Nob3BfYmFja191cmw+PG9yZGVyX25vPnNiYi10ZXN0LTM2Nzwvb3JkZXJfbm8+PG9yZGVyX2Nhc2hfYW1vdW50PjEwMDAwPC9vcmRlcl9jYXNoX2Ftb3VudD48b3JkZXJfc2hpcF9kYXRlPjIzLzAyLzIwMTg8L29yZGVyX3NoaXBfZGF0ZT48b3JkZXJfc2hpcF9kYXlzPjA8L29yZGVyX3NoaXBfZGF5cz48b3JkZXJfZGVzY3JpcHRpb24+VGhhbmsreW91K2ZvcitwdXJjaGFzaW5nK1Rlc3QrVGVzdCUyQytlbmpveSt0aGlzK3dvbmRlcmZ1bCtldmVudC48L29yZGVyX2Rlc2NyaXB0aW9uPjx2YWxpZGl0eV90aW1lPjIwMTgwMjIzMDMwMjQyPC92YWxpZGl0eV90aW1lPjxub3RpZnlfdXJsPmh0dHA6Ly90ZXN0LnNiYmhvc3RlZC5jb20vcGF5bWVudHMvbm90aWZ5LzM2Ny9wYXlvbzwvbm90aWZ5X3VybD48Y3VzdG9tZXI+PG5hbWU+Y3VzdG9tZXIgdGhlZGlsYWJAZ21haWwuY29tPC9uYW1lPjxwaG9uZT4xMjM0NTY3ODwvcGhvbmU+PGVtYWlsPnRoZWRpbGFiQGdtYWlsLmNvbTwvZW1haWw+PC9jdXN0b21lcj48L3Nob3A+PC9zaG9wcz48U3RhdGU+UEFZTUVOVF9SRUNFSVZFRDwvU3RhdGU+PFBheW1lbnRNZXRob2Q+RV9XQUxMRVQ8L1BheW1lbnRNZXRob2Q+PC9QYXltZW50Tm90aWZpY2F0aW9uPg==</Data>' .
                '<Signature>' . $signature . '</Signature>' .
                '<PayooSessionID>WGyCpLu5V2/2L9J9Bn5MBmXG22UNkxbZUoc2ww2BDOWIHly9bKj0clVjEhxWhIJFva+fCDrryu14Do2bH6qUGg==</PayooSessionID>' .
                '<KeyFields>' . $keyFields . '</KeyFields>' .
                '</PayooConnectionPackage>'
        ];

        $this->request->setApiUsername('iss_pulse_active');
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
