<?php

namespace ditibal\smssender;

use Yii;
use yii\base\Component;
use ditibal\smssender\queue\SendSmsJob;

class Sms extends Component
{
    public $sender;
    public $phone;
    public $message;
    private $_async;


    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function async()
    {
        $this->_async = true;
        return $this;
    }

    public function send()
    {
        if ($this->sender->queue && !$this->_async) {
            $this->sender->queue->push(new SendSmsJob([
                'sms' => $this,
            ]));

            return true;
        }

        return $this->sender->send($this);
    }
}
