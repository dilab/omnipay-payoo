<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 5/2/18
 * Time: 10:33 AM
 */

namespace Omnipay\Payoo\Message;


use Cake\Chronos\Chronos;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var  PurchaseRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new PurchaseRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->request);
    }

    public function testGetData()
    {
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

        $result = $this->request->getData();

        $orderShipDays = 0;
        $orderShipDate = Chronos::parse('+1 day')->format('d/m/Y');
        $validityTime = Chronos::parse('+24 hours')->format('YmdHms');
        
        $orderXml =
            '<shops><shop>' .
            '<session>' . $orderNo . '</session>' .
            '<username>' . $username . '</username>' .
            '<shop_id>' . $shopId . '</shop_id>' .
            '<shop_title>' . $shopTitle . '</shop_title>' .
            '<shop_domain>' . $shopDomain . '</shop_domain>' .
            '<shop_back_url>' . $returnUrl . '</shop_back_url>' .
            '<order_no>' . $orderNo . '</order_no>' .
            '<order_cash_amount>' . 10000 . '</order_cash_amount>' .
            '<order_ship_date>' . $orderShipDate . '</order_ship_date>' .
            '<order_ship_days>' . $orderShipDays . '</order_ship_days>' .
            '<order_description>' . urlencode($description) . '</order_description>' .
            '<validity_time>' . $validityTime . '</validity_time>' .
            '<notify_url>' . $notifyUrl . '</notify_url>' .
            '<customer>' .
            '<name>' . $firstName . ' ' . $lastName . '</name>' .
            '<phone>' . $phone . '</phone>' .
            '<email>' . $email . '</email>' .
            '</customer>' .
            '</shop></shops>';

        $expected = [
            'bc' => '',
            'pm' => '',
            'OrdersForPayoo' => $orderXml,
            'CheckSum' => hash('sha512', $secretKey . $orderXml),
        ];

        $this->assertSame($expected, $result);
    }

    public function testSendData()
    {
        $this->request->initialize([]);

        $data = ['test' => 'data'];

        $this->assertInstanceOf(
            PurchaseResponse::class,
            $this->request->sendData($data)
        );
    }

    public function testDescriptionValidation()
    {
        $this->request->initialize([
            'card' => [
                'firstName' => $firstName = 'Xu',
                'lastName' => $lastName = 'Ding',
                'email' => $email = 'xuding@spacebib.com',
                'number' => $phone = '93804194'
            ],
            'description' => $description = '<p>Too short</p>',
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

        $this->setExpectedException(InvalidRequestException::class);

        $this->request->getData();
    }
}
