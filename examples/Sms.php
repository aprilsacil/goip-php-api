<?php
// manual autoload
spl_autoload_register(function($class) {
    include dirname(__DIR__).'/src/' . str_replace('GoIP\\', '', $class . '.php');

});

$goip = new \GoIP\GoipClient("192.168.0.105", "admin", "admin", 80);

$sms = new \GoIP\Sms($goip);
$messages = $sms->getMessages();
print_r($messages);

$lineMessages = $sms->getLineMessages(2);
print_r($lineMessages);

$result = $sms->sendSms(2, '09158786696', 'Yey!');
print_r($result);
