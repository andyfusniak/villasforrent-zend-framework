<?php

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath('/../library'),
    get_include_path(),
)));

require_once 'Zend/Mail.php';
require_once 'Zend/Mail/Transport/Smtp.php';
$config = array(
				'auth' => 'login',
                'username' => 'andyfusniak@gmail.com',
		        'password' => 'technological2',
				'ssl' => 'ssl',
				'port' => 465);

$transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
Zend_Mail::setDefaultTransport($transport);


$mail = new Zend_Mail();
$mail->setBodyText("This is the text of the mail.\n\nAcross a few\nlines.");
//$mail->setBodyHtml("<p>Some<strong>HTML</strong> inside<br />a message</p>");
$mail->setFrom('andy@greycatmedia.co.uk', 'Some Sender');
$mail->addTo('andy@greycatmedia.co.uk', 'Some Recipient');
$mail->setSubject('TestSubject');
$mail->send();
