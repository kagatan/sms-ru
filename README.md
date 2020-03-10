# sms-ru
https://sms.ru/

## Установка

Для установки данного пакета выполните в терминале следующую команду:

```shell
$ composer require kagatan/sms-ru
```


```php
<?php

use Kagatan\SmsRu\SmsRuClient;
use Kagatan\SmsRu\SmsRuMessage;

$key = 'AA9A60BBCC-DDA8-FFGG-HH-DDCDDEDDB7DD'; 

$smsMessage = SmsRuMessage::create()
            ->key($key)
            ->content('Hello')
            ->to('380930001122')
            ->toArray();
         
$smsClient = new SmsRuClient();
$id = $smsClient->send($smsMessage);

if ($smsClient->hasErrors()) {
    echo $this->getErrors();
}

```
