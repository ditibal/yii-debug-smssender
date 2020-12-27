<?php

namespace ditibal\smssender;

use Yii;
use yii\base\Component;
use ditibal\smssender\queue\SendSmsJob;
use yii\base\InvalidArgumentException;

class Sms extends Component
{
    public $sender;
    public $phone;
    public $message;
    private $_async;
    private $pushDelay;


    private function clearPhone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone) == 11 && $phone[0] == 8) {
            $phone[0] = '7';
        }

        if (strlen($phone) == 10) {
            $phone = '7' . $phone;
        }

        return $phone;
    }

    public function setPhone($phone)
    {
        $phone = $this->clearPhone($phone);

        if (!preg_match('/\d{11}/', $phone)) {
            throw new InvalidArgumentException('Phone is invalid.');
        }

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

    public function delay($value)
    {
        $this->pushDelay = $value;
        return $this;
    }

    public function send()
    {
        if ($this->sender->queue && !$this->_async) {
            $this->sender
                ->queue
                ->delay($this->pushDelay)
                ->push(new SendSmsJob([
                    'sms' => $this,
                ]));

            return true;
        }

        return $this->sender->send($this);
    }
}
