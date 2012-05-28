<?php

$strong = null;

// 2048 bits of entropy
$entropy = openssl_random_pseudo_bytes(256, $strong);

var_dump($strong);

echo strlen($entropy) . ' bytes' . PHP_EOL;
echo 8 * strlen($entropy) . ' bit key' . PHP_EOL;

$key = hash("sha256", $entropy);

echo "key length = " . strlen($key) . " bytes" . PHP_EOL;
echo $key . PHP_EOL;
//echo bin2hex($entropy) . PHP_EOL . PHP_EOL;


//echo base64_encode($entropy) . PHP_EOL . PHP_EOL;
