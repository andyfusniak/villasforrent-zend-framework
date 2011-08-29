<?php
require_once 'hpwlib.php';

$apikey             = "f409a10c182a1fb7a8ebaea5cad7af1cc82486ad";

try {
    $hpwApi = new HpwApi($apikey);
    $calendar = $hpwApi->getCalendar($idProperty=10445, $idCalendar=22);
    echo $calendar;

    $c = json_decode($calendar, false);
} catch (ApiKeyMissingException $e) {
    echo "you forgot the api key\n";
} catch (PropertyMissingException $e) {
    echo "you did not specify the property id";
}
