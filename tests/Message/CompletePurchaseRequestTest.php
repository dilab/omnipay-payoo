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

    public $getQuery = [
        'session' => 'SS7981',
        'order_no' => 'ORD77823',
        'status' => '1',
        'checksum' => '60a762dc6b241bec39edb7a0484405cd803a363472e26cb5ede9af1db8ce3846463996836'
    ];

    public $postData = [
        'NotifyData' => '<?xml version="1.0"?>' .
            '<PayooConnectionPackage xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">' .
            '<Data>PFBheW1lbnROb3RpZmljYXRpb24+PHNob3BzPjxzaG9wPjxzZXNzaW9uPjE1MDg5MDEzNDc8L3Nlc3Npb24+PHVzZXJuYW1lPmlzc19zaG9wX2NsaWVudDwvdXNlcm5hbWU+PHNob3BfaWQ+NjkxPC9zaG9wX2lkPjxzaG9wX3RpdGxlPnNob3BfY2xpZW50PC9zaG9wX3RpdGxlPjxzaG9wX2RvbWFpbj5odHRwOi8vbG9jYWxob3N0PC9zaG9wX2RvbWFpbj48c2hvcF9iYWNrX3VybD5odHRwOi8vbG9jYWxob3N0OjgwODAvQ2hlY2tzdW1fRGVtb19waHBfdjIvdmVyaWZ5UmVzcG9uc2UucGhwPC9zaG9wX2JhY2tfdXJsPjxvcmRlcl9ubz4xNTA4OTAxMzQ3PC9vcmRlcl9ubz48b3JkZXJfY2FzaF9hbW91bnQ+MTAwMDA8L29yZGVyX2Nhc2hfYW1vdW50PjxvcmRlcl9zaGlwX2RhdGU+MjUvMTAvMjAxNzwvb3JkZXJfc2hpcF9kYXRlPjxvcmRlcl9zaGlwX2RheXM+MTwvb3JkZXJfc2hpcF9kYXlzPjxvcmRlcl9kZXNjcmlwdGlvbj5PcmRlcitObyUzQSslNUIxNTA4OTAxMzQ3JTVELiUzQyUyRmJyJTNFUHJvZHVjdCtpbmZvJTJDK3RyYXZlbCtpbmZvK29yK3NlcnZpY2UraW5mby4uLiUyOGV4JTNBKyUyOSUzQyUyRmJyJTNFTW9uZXkrdG90YWwlM0ErMTAwMDA8L29yZGVyX2Rlc2NyaXB0aW9uPjx2YWxpZGl0eV90aW1lPjIwMTcxMDI2MDUxNTQ3PC92YWxpZGl0eV90aW1lPjxub3RpZnlfdXJsPmh0dHA6Ly8xOTIuMTY4LjExLjMxOjgzMzMvTm90aWZ5TGlzdGVuZXIuYXNweAk8L25vdGlmeV91cmw+PGN1c3RvbWVyPjxuYW1lPk5ndXllbiBWYW4gVmluaDwvbmFtZT48cGhvbmU+MDkwMDExMTExMTwvcGhvbmU+PGFkZHJlc3M+MzUgTmd1eWVuIEh1ZSwgUGh1b25nIEJlbiBOZ2hlLCBRdWFuIDEsIFRwIEhvIENoaSBNaW5oPC9hZGRyZXNzPjxjaXR5PjI0MDAwPC9jaXR5PjxlbWFpbD5lbWFpbEB5YWhvby5jb208L2VtYWlsPjwvY3VzdG9tZXI+PC9zaG9wPjwvc2hvcHM+PFN0YXRlPlBBWU1FTlRfUkVDRUlWRUQ8L1N0YXRlPjxQYXltZW50TWV0aG9kPkVfV0FMTEVUPC9QYXltZW50TWV0aG9kPjwvUGF5bWVudE5vdGlmaWNhdGlvbj4=</Data>' .
            '<Signature>CB87D58C18E4D6BAE5949D9C21A61C997671D4304CCFF519B82C5E0739453F5780781CEDFD4831885EDD3D7A34EC5E4BB80CCA43B6C45C9FDFB33626BF4F71C5</Signature>' .
            '<PayooSessionID>WGyCpLu5V2/2L9J9Bn5MBmXG22UNkxbZUoc2ww2BDOWIHly9bKj0clVjEhxWhIJFva+fCDrryu14Do2bH6qUGg==</PayooSessionID>' .
            '<KeyFields>PaymentMethod|State|Session|BusinessUsername|ShopID|ShopTitle|ShopDomain|ShopBackUrl|OrderNo|OrderCashAmount|StartShippingDate|ShippingDays|OrderDescription|NotifyUrl|PaymentExpireDate</KeyFields>' .
            '</PayooConnectionPackage>'
    ];

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
        $this->getHttpRequest()->query->replace($this->getQuery);

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
        $this->getHttpRequest()->request->replace($this->postData);
        $data = $this->request->getData();
    }

}
