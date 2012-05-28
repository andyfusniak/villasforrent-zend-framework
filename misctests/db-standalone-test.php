<?php
require_once 'Zend/Db/Adapter/Pdo/Mysql.php';
require_once 'Zend/Db/Table/Abstract.php';

// connection
$db = new Zend_Db_Adapter_Pdo_Mysql(
    array (
        'host'     => 'localhost',
        'username' => 'root',
        'password' => 'mrgrey',
        'dbname'   => 'barrelliski'
    )
);

Zend_Db_Table_Abstract::setDefaultAdapter($db);

$db->setFetchMode(Zend_Db::FETCH_OBJ);


// query

$sql = "SELECT l.firstname as wibble, b.startDate FROM Bookings AS b INNER JOIN GroupLeaders AS l ON b.idGroupLeaders = l.idGroupLeaders LIMIT 1";

$result = $db->fetchPairs($sql);

var_dump($result);
