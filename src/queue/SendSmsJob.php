<?php

namespace ditibal\smssender\queue;

use Yii;
use yii\base\BaseObject;

use yii\queue\JobInterface;

class SendSmsJob extends BaseObject implements JobInterface
{
    public $sms;


    public function execute($queue)
    {
        $sms = $this->sms;

        $sender = $this->sms->sender;
        $sms->async();

        return $sender->send($sms);
    }
}
