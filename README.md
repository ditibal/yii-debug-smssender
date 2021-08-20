# yii2-smssender

[![Github Actions Status](https://github.com/ditibal/yii2-smssender/workflows/PHP%20CI/badge.svg)](https://github.com/ditibal/yii2-smssender/actions)

Установка
------------


```
php composer.phar require ditibal/yii2-smssender
```

Настройка
------------

Добавьте конфигурацию в components:

```php
return [
    'components' => [
        'smsSender' => [
            'class' => 'ditibal\smssender\SmsSender',            
            'transport' => [
                'class' => 'ditibal\smssender\transports\MtsCommunicatorTransport',
                'token' => '<TOKEN>',
            ],
        ],
    ],
];
```


Использование
------------
```php
	Yii::$app->smsSender
	    ->compose()
	    ->setPhone('+7 (999) 000-00-00')
	    ->setMessage('Сообщение')
	    ->send();
```

Очередь
------------
Сообщения могут отправляться через очередь. Для этого нужно установить и настроить пакет [yiisoft/yii2-queue](https://github.com/yiisoft/yii2-queue):

```
php composer.phar require --prefer-dist yiisoft/yii2-queue
```

```php
return [
    'components' => [
        'smsQueue' => [
            'class' => \yii\queue\sync\Queue::class,
            'handle' => false, // whether tasks should be executed immediately            
        ],
        'smsSender' => [
            'class' => 'ditibal\smssender\SmsSender',            
            'queue' => 'smsQueue', // <-- Указать имя компонента очереди
            'transport' => [
                'class' => 'ditibal\smssender\transports\MtsCommunicatorTransport',
                'token' => '<TOKEN>',
            ],
        ],
    ],
];
```
