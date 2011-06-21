<?php
$apikey             = "356a192b7913b04c54574d18c28d46e6395428ab";
$service_baseurl    = "http://beta.villasforrent.net/api";
$service_resource   = "property";
$idProperty         = "10000";
$passwd             = "v1ll454r3nt.!";

$username = "fonfy";
$password = "Fonfy12345.Fonfy12345.";

// generate the signature
$digestkey = sha1($apikey . $service_resource . $idProperty . $passwd);

// setup the resource
$headers = array (
    'x-apikey: ' . $apikey,
    'x-digestkey: ' . $digestkey,
    'Accept: application/vnd.vfr.rate+json; version: 1.0;'
);

$service_uri = $service_baseurl . '/' . $service_resource . '/' . $idProperty;

$ch = curl_init($service_uri);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_HEADER, false);

// connect to the resource
$curl_response = curl_exec($ch);
//var_dump($curl_response);
//die();

// close the resource
curl_close($ch);

// write to std output
var_dump(json_decode($curl_response, true)) . "\n";
