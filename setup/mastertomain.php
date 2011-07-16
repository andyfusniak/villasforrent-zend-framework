<?php
$config['dest']['dbhost'] = 'localhost';
$config['dest']['dbuser'] = 'root';
$config['dest']['dbname'] = 'villasforrent';
$config['dest']['dbpass'] = 'mrgrey';

$config['dest']['dbhost'] = 'localhost';
$config['dest']['dbuser'] = 'beta';
$config['dest']['dbname'] = 'hpw_live';
$config['dest']['dbpass'] = 'beta4046';

// connect to the source database
$dest_link = mysqli_connect($config['dest']['dbhost'],
							$config['dest']['dbuser'],
							$config['dest']['dbpass'],
							$config['dest']['dbname'])
			or die('Could not connect to destination db server.');
			

$sql = "SELECT * FROM PropertiesContent WHERE version = 1 ORDER BY idProperty, idPropertyContentField ASC";

$result = mysqli_query($dest_link, $sql);
while ($row = mysqli_fetch_object($result)) {
	$update_sql = "UPDATE PropertiesContent SET content='%A' WHERE idProperty=%B AND idPropertyContentField=%C AND version = 2";
	$update_sql = str_replace("%A", mysqli_real_escape_string($dest_link, $row->content), $update_sql);
	$update_sql = str_replace("%B", $row->idProperty, $update_sql);
	$update_sql = str_replace("%C", $row->idPropertyContentField, $update_sql);
	
	echo $update_sql ."\n";
	
	$result_dest = mysqli_query($dest_link, $update_sql) or die('MySQL error' . mysqli_error($dest_link));	
}
