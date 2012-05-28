<?php
require_once 'hpwlib.php';

$apikey = "f409a10c182a1fb7a8ebaea5cad7af1cc82486ad";

try {
    $hpwApi = new HpwApi($apikey, $serviceUri="http://mars.zendvfr/api");

    $photos = $hpwApi->getAllPhotos($idProperty=10191);
    echo $photos;

    $p = json_decode($photos, false);
} catch (ApiKeyMissingException $e) {
    echo "you forgot the api key\n";
} catch (PropertyMissingException $e) {
    echo "you did not specify the property id";
}
