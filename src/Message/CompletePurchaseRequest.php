<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 5/2/18
 * Time: 10:03 AM
 */

namespace Omnipay\Payoo\Message;


class CompletePurchaseRequest extends AbstractRequest
{

    public function getData()
    {

        if ($this->httpRequest->isMethod('get')) {

            $data = $this->httpRequest->query->all();

            $data['computed_checksum'] = hash('sha512',
                $this->getSecretKey() .
                $this->httpRequest->query->get('session') . '.' .
                $this->httpRequest->query->get('order_no') . '.' .
                $this->httpRequest->query->get('status')
            );

            $data['method'] = 'get';

            return $data;
        }

        $xmlData = $this->httpRequest->request->get('NotifyData');

        $doc = new \DOMDocument();

        $doc->loadXML($xmlData);

        $signature = $doc->getElementsByTagName('Signature')->item(0)->nodeValue;

        $keyFields = $doc->getElementsByTagName('KeyFields')->item(0)->nodeValue;

        $data = $doc->getElementsByTagName('Data')->item(0)->nodeValue;

        $dataDecodedXml = base64_decode($data);

        $dataDoc = new \DOMDocument();

        $dataDoc->loadXML($dataDecodedXml);

        return [
            'order_no' => $this->withBillingCode($dataDoc) ?
                $this->readNodeValue($dataDoc, 'OrderNo') :
                $this->readNodeValue($dataDoc, 'order_no'),
            'state' => $this->readNodeValue($dataDoc, 'State'),
            'signature' => $signature,
            'computed_checksum' => $this->computedSignature($dataDoc, $keyFields),
            'method' => 'post',
        ];
    }

    public function sendData($data)
    {
        return new CompletePurchaseResponse($this, $data);
    }

    private function computedSignature(\DOMDocument $dataDoc, $keyFields)
    {
        $fields = explode('|', $keyFields);

        $values = array_map(function ($name) use ($dataDoc) {
            return $this->readNodeValueOfDataDoc($dataDoc, $name);
        }, $fields);

        array_unshift($values, $this->getSecretKey());

        $str = implode('|', $values);

        return hash('sha512', $str);
    }

    private function readNodeValueOfDataDoc(\DOMDocument $doc, $tagName)
    {
        $withBillingCode = $this->withBillingCode($doc);

        $default = [
            'PaymentMethod' => '',
            'State' => '',
            'Session' => '',
            'BusinessUsername' => '',
            'ShopID' => 0,
            'ShopTitle' => '',
            'ShopDomain' => '',
            'ShopBackUrl' => '',
            'OrderNo' => '',
            'OrderCashAmount' => 0,
            'StartShippingDate' => '',
            'ShippingDays' => 0,
            'OrderDescription' => '',
            'NotifyUrl' => '',
            'BillingCode' => '',
            'PaymentExpireDate' => '',
        ];

        $defaultTag = $default[$tagName];

        $readTag = $this->readNodeValue($doc, $this->tagNameKey($tagName, $withBillingCode));

        if ('' === $readTag) {
            return $defaultTag;
        }

        if ('OrderDescription' == $tagName) {
            return urldecode($readTag);
        }

        return $readTag;
    }

    private function readNodeValue(\DOMDocument $doc, $tagName)
    {
        $nodeList = $doc->getElementsByTagname($tagName);

        $tempNode = $nodeList->item(0);

        if ($tempNode == null) {
            return '';
        }

        return $tempNode->nodeValue;
    }

    private function tagNameKey($fieldName, $withBillingCode)
    {
        if ($withBillingCode) {

            $map = ['ShopID' => 'ShopId'];

            if (!isset($map[$fieldName])) {
                return $fieldName;
            }

            return $map[$fieldName];
        }

        $map = [
            'Session' => 'session',
            'BusinessUsername' => 'username',
            'ShopID' => 'shop_id',
            'ShopTitle' => 'shop_title',
            'ShopDomain' => 'shop_domain',
            'ShopBackUrl' => 'shop_back_url',
            'OrderNo' => 'order_no',
            'OrderCashAmount' => 'order_cash_amount',
            'StartShippingDate' => 'order_ship_date',
            'ShippingDays' => 'order_ship_days',
            'OrderDescription' => 'order_description',
            'NotifyUrl' => 'notify_url',
            'State' => 'State',
            'PaymentMethod' => 'PaymentMethod',
            'PaymentExpireDate' => 'validity_time',
        ];

        if (!isset($map[$fieldName])) {
            return $fieldName;
        }

        return $map[$fieldName];
    }

    private function withBillingCode(\DOMDocument $doc)
    {
        return ('' != $this->readNodeValue($doc, 'BillingCode'));
    }
}