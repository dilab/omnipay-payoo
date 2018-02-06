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
            'state' => $this->readNodeValue($dataDoc, 'State'),
            'signature' => $signature,
            'computed_checksum' => $this->computedSignature($dataDoc, $keyFields),
            'method' => 'post',
        ];
    }

    public function sendData($data)
    {
    }

    private function computedSignature(\DOMDocument $dataDoc, $keyFields)
    {
        $fields = explode('|', $keyFields);

        $values = array_map(function ($name) use ($dataDoc) {
            return $this->readNodeValue($dataDoc, $name);
        }, $fields);

        array_unshift($values, $this->getSecretKey());

        $str = implode('|', $values);

        return hash('sha512', $str);
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

}