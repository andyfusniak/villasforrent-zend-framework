<?php
date_default_timezone_set('Europe/London');
$d = new Datetime('2010-02-28 14:00:00');
//var_dump($d);

echo "Start: " . date('Y-m-d h:i:s', $d->getTimestamp()) . "\n\n";

//$nd = $d->sub(new DateInterval('P1D'));
//var_dump($nd);
echo "Back one day: " . date('Y-m-d h:i:s', $d->sub(new DateInterval('P1D'))->getTimestamp()) . "\n\n";

$nd = $d->add(new DateInterval('P1D'));
//var_dump($nd);
echo "Forward to start: " . date('Y-m-d h:i:s', $nd->getTimestamp()) . "\n\n";
