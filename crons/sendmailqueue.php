<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'live'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

$application = new Zend_Application(
    APPLICATION_ENV,
    array(
        'bootstrap' => array(
            'class' => 'Cron',
            'path' => APPLICATION_PATH . '/Cron.php',
        ),
        'config' => APPLICATION_PATH . '/configs/application.ini'
    )
);

$application->bootstrap();

// get the the configuration
$bootstrap = $application->getOptions();
$options = array(
    'name'          => 'sendmail',
    'driverOptions' => array(
        'host'      => $bootstrap['resources']['db']['params']['host'],
        'port'      => '3306',
        'username'  => $bootstrap['resources']['db']['params']['username'],
        'password'  => $bootstrap['resources']['db']['params']['password'],
        'dbname'    => $bootstrap['resources']['db']['params']['dbname'],
        'type'      => 'pdo_mysql'
    )
);
$queue = new Zend_Queue('Db', $options);

$count = count($queue);

if ($count == 0)
    return;



$messages = $queue->receive(intval($count));

// iterate through the queue
foreach($messages as $msg) {
    // prepare to send a UTF-8 email (both text and html combined)
    $mail = new Zend_Mail('utf-8');

    $params = unserialize($msg->body);

    // set the receipt
    $mail->addTo($params['to']);
    $mail->setSubject($params['subject']);

    // set the HTML and TXT bodies and send
    $mail->setBodyText($params['bodyText']);
    if ($params['bodyHtml'])
        $mail->setBodyHtml($params['bodyHtml']);

    // send this email
    try {
        $mail->send();

        // remove the message from the queue
        $queue->deleteMessage($msg);

        unset($mail);
    } catch (Exception $e) {
        throw $e;
    }
}
