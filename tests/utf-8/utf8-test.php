<?php

$s = "กกก";

echo $s . "\n";

echo "strlen=" . strlen($s) . "\n";
echo "mb_strlen=" . mb_strlen($s, 'utf-8') . "\n";

echo sha1($s) . "\n";
