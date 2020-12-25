<?php

namespace ditibal\smssender;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Component;
use yii\di\Instance;
use yii\queue\Queue;
use ditibal\smssender\queue\SendSmsJob;

class SmsSender extends Component
{
    public $queue;
    private $_transport = [];


    public function init()
    {
        parent::init();

        if ($this->queue) {
            $this->queue = Instance::ensure($this->queue, Queue::class);
        }
    }

    public function compose()
    {
        $config = [
            'class' => '\ditibal\smssender\Sms',
            'sender' => $this,
        ];

        return Yii::createObject($config);
    }

    public function setTransport($transport)
    {
        if (!is_array($transport) && !is_object($transport)) {
            throw new InvalidConfigException('"' . get_class($this) . '::transport" should be either object or array, "' . gettype($transport) . '" given.');
        }

        $this->_transport = Yii::createObject($transport);
    }

    public function getTransport()
    {
        return $this->_transport;
    }

    public function send($sms)
    {
        if (empty($this->transport)) {
            throw new InvalidConfigException('SmsSender::transport must be set.');
        }

        return $this->transport->send($sms);
    }
}
