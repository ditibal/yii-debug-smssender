<?php

namespace ditibal\smssender\transports;

use yii\base\Component;
use yii\httpclient\CurlTransport;
use yii\httpclient\Client;
use yii\httpclient\Response;

class MtsMarketologTransport extends Component
{
    public string $host = 'https://omnichannel.mts.ru/http-api/v1/';
    public string $login;
    public string $password;
    public string $naming;
    private string $auth;

    public function init()
    {
        parent::init();

        $this->auth = "Basic " . base64_encode($this->login . ":" . $this->password);
    }

    public function send($sms): Response
    {
        $naming = $this->naming;
        $phone = $sms->phone;
        $message = $sms->message;

        $client = new Client([
            'baseUrl' => $this->host,
            'transport' => CurlTransport::class
        ]);

        $data = [
            "messages" => [
                [
                    "content" => [
                        "short_text" => $message
                    ],
                    "to" => [
                        [
                            "msisdn" => $phone
                        ]
                    ],
                ]
            ],
            "options" => [
                "class" => 1,
                "from" => [
                    "sms_address" => $naming,
                ],
            ]
        ];

        return $client->createRequest()
            ->setMethod('POST')
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl('messages')
            ->addHeaders(['Authorization' => $this->auth])
            ->setData($data)
            ->send();
    }
}
