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
        $billingCode = '123456';
        $orderNo = '1508901347';
        $orderCashAmount = 10000;
        $state = 'PAYMENT_RECEIVED';
        $paymentMethod = 'E_WALLET';
        $shopId = '691';
        $paymentExpireDate = '25/10/2017';

        $signature = hash('sha512',
            $str = (
                '73b3f5b8efa2c3654b75bf6f5afb76d0' . '|' .
                $billingCode . '|' .
                $orderNo . '|' .
                $orderCashAmount . '|' .
                $state . '|' .
                $paymentMethod . '|' .
                $shopId . '|' .
                $paymentExpireDate
            )
        );

        $keyFields = 'BillingCode|OrderNo|OrderCashAmount|State|PaymentMethod|ShopId|PaymentExpireDate';

        $data = base64_encode('<PaymentNotification>' .
            '<shops><shop>' .
            '<session>1508901347</session>' .
            '<username>iss_shop_client</username><shop_id>691</shop_id><shop_title>shop_client</shop_title>' .
            '<shop_domain>http://localhost</shop_domain><shop_back_url>http://localhost:8080/Checksum_Demo_php_v2/verifyResponse.php</shop_back_url>' .
            '<order_no>1508901347</order_no><order_cash_amount>10000</order_cash_amount><order_ship_date>25/10/2017</order_ship_date>' .
            '<order_ship_days>1</order_ship_days><order_description>10000</order_description><validity_time>20171026051547</validity_time>' .
            '<notify_url>http://192.168.11.31:8333/NotifyListener.aspx	</notify_url>' .
            '<customer><name>Nguyen Van Vinh</name>' .
            '<phone>0900111111</phone><address>35 Nguyen Hue, Phuong Ben Nghe, Quan 1, Tp Ho Chi Minh</address>' .
            '<city>24000</city><email>email@yahoo.com</email></customer>' .
            '</shop></shops>' .
            '<ShopId>' . $shopId . '</ShopId>' .
            '<BillingCode>' . $billingCode . '</BillingCode>' .
            '<OrderNo>' . $orderNo . '</OrderNo>' .
            '<OrderCashAmount>' . $orderCashAmount . '</OrderCashAmount>' .
            '<State>' . $state . '</State>' .
            '<PaymentMethod>' . $paymentMethod . '</PaymentMethod>' .
            '<PaymentExpireDate>' . $paymentExpireDate . '</PaymentExpireDate>' .
            '</PaymentNotification>'
        );


        $postData = [
            'NotifyData' => '<?xml version="1.0"?>' .
                '<PayooConnectionPackage xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">' .
                '<Data>' . $data . '</Data>' .
                '<Signature>' . $signature . '</Signature>' .
                '<PayooSessionID>123</PayooSessionID>' .
                '<KeyFields>' . $keyFields . '</KeyFields>' .
                '</PayooConnectionPackage>'
        ];

        $this->getHttpRequest()->setMethod('post');

        $this->getHttpRequest()->request->replace($postData);

        $data = $this->request->getData();

        $expected = [
            'state' => $state,
            'signature' => $signature,
            'computed_checksum' => $signature,
            'method' => 'post'
        ];

        $this->assertEquals($expected, $data);
    }

}
