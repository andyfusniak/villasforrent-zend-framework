<?php
require 'Zend/Mail.php';

$mail = new Zend_Mail();
$mail->setBodyText('This is the text of the mail.');
$mail->setFrom('andy@greycatmedia.co.uk', 'Some Sender');
$mail->addTo('andy@greycatmedia.co.uk', 'Some Recipient');
$mail->setSubject('TestSubject');
$mail->send();
