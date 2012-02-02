<?php

$subject = "search/aa/bb/cc/dd";
//$pattern = "#in/(?<categories>[^/]+/){1,10}(?<productname>[^/]+)#i";
//$pattern = "#((([a-z0-9-]+)/)?){1,}#i";
$pattern = "#^(search/)([^/]+)#i";

//echo "nothing useful";
//preg_match($pattern, $subject, $matches);
//print_r($matches);

echo "useful for variable depths";
preg_match_all($pattern, $subject, $matches);
print_r($matches);

