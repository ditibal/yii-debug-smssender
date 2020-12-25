<?php

namespace ditibal\smssender\transports;

use Yii;
use yii\base\Component;
use yii\di\Instance;
use yii\base\InvalidConfigException;

class DebugTransport extends Component
{
    public $mailer;
    public $message;


    public function init()
    {
        parent::init();

        if (empty($this->message['to'])) {
            throw new InvalidConfigException('The "to" option must be set for DebugTransport::message.');
        }

        if (empty($this->message['from'])) {
            throw new InvalidConfigException('The "from" option must be set for DebugTransport::message.');
        }

        $this->mailer = Instance::ensure($this->mailer, 'yii\mail\BaseMailer');
    }

    public function send($sms)
    {
        $from = $this->message['from'];
        $to = $this->message['to'];
        $subject = $this->message['subject'] ?? 'Sms';

        $body = "Phone: {$sms->phone}\n";
        $body .= "Message: {$sms->message}";

        return $this->mailer->compose()
            ->setTextBody($body)
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->send();
    }
}
