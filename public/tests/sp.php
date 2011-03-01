<?php
$d = new Datetime('2010-11-01 14:00:00');
echo "Start: " . date('Y-m-d h:i:s', $d->getTimestamp()) . "\n\n";
echo "Back one day: " . date('Y-m-d h:i:s', $d->sub(new DateInterval('P1D'))->getTimestamp()) . "\n\n";
echo "Forward to start: " . date('Y-m-d h:i:s', $d->add(new DateInterval('P1D'))->getTimestamp()) . "\n\n";
?>
