<?php

namespace ditibal\smssender\transports;

use Yii;
use yii\base\Component;
use SoapClient;

class MtsCommunicatorTransport extends Component
{
    public $token;
    public $wsdl = 'https://api.mcommunicator.ru/m2m/m2m_api.asmx?wsdl';


    public function send($sms)
    {
        $phone = $sms->phone;
        $message = $sms->message;

        $httpHeaders = [
            'http' => [
                'protocol_version' => 1.1,
                'header' => 'Authorization:Bearer ' . $this->token,
            ],
        ];

        $context = stream_context_create($httpHeaders);

        $params = [
            'stream_context' => $context,
            'trace' => 1,
            'exceptions' => 0,
        ];

        $client = new SoapClient($this->wsdl, $params);

        $result = $client->SendMessage([
            'msid' => $phone,
            'message' => $message,
        ]);

        if (is_soap_fault($result)) {
            return false;
        }

        return true;
    }
}
