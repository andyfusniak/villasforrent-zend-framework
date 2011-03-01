<?php
$d = new Datetime('2010-11-01 14:00:00');

var_dump($d);

$nd = $d->sub(new DateInterval('P1D'));

echo date('Y-m-d h:i:s', $nd->getTimestamp()) . "\n\n";