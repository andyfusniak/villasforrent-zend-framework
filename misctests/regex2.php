<?php

$subject = "search/aa/bb/cc";
//$pattern = '([^/]+)';
$pattern = "([^/]+)+";
preg_match($pattern, $subject, $matches);
print_r($matches);
