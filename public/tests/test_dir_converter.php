<?php
$idProperty = 12299;

$range = $idProperty - 10000;

echo "Range: " . $range . "\n";

echo  10000 + (floor($range/50) * 50);
