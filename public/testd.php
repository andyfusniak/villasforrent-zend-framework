<?php
$begin = new DateTime( '2007-12-31' );
$end = new DateTime( '2009-12-31 23:59:59' );

$interval = DateInterval::createFromDateString('last thursday of next month');
$period = new DatePeriod($begin, $interval, $end);

foreach ( $period as $dt )
  echo $dt->format( "l Y-m-d H:i:s\n" ) . '<br />';

