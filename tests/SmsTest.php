<?php


use ditibal\smssender\SmsSender;
use PHPUnit\Framework\TestCase;

class SmsTest extends TestCase
{
    public function testWrongPhone()
    {
        $this->expectException(\yii\base\InvalidArgumentException::class);
        $sender = new SmsSender();
        $sender->compose()->setPhone('78987987');
    }

    public function testCorrectPhone()
    {
        $phone = '79000000000';
        $sender = new SmsSender();
        $sms = $sender->compose()->setPhone($phone);

        $this->assertEquals($phone, $sms->phone);
    }
}