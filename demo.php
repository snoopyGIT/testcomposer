<?php

require __DIR__ . '/vendor/autoload.php';

use Snoopy\TestSignature\Decrypt\Lib\Signature;

$params = [
    'id' => 1,
];
$secret = '123456';
$signature = Signature::getSignature($params, $secret);

echo $signature;
