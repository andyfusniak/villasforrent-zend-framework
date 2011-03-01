<?php
$d = new DateTime('2010-11-01 14:00:00');
$d->sub(new DateInterval('P1D'));
echo $d->format('Y-m-d');
