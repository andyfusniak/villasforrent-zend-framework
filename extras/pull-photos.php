<?php
require_once 'hpwlib.php';

$apikey = "f409a10c182a1fb7a8ebaea5cad7af1cc82486ad";

try {
    $hpwApi = new HpwApi($apikey, $serviceUri="http://www.holidaypropertyworldwide.com/api");

    $photos = $hpwApi->getAllPhotos($idProperty=10488);
    echo $photos;

    $p = json_decode($photos, false);
} catch (ApiKeyMissingException $e) {
    echo "you forgot the api key\n";
} catch (PropertyMissingException $e) {
    echo "you did not specify the property id";
}
