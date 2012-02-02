<?php
set_include_path('../library');


require_once 'Zend/Mail.php';
require_once 'Zend/Mail/Transport/Sendmail.php';
require_once 'Zend/View.php';

$html = new Zend_View();

$html->setScriptPath('./emails/');

$html->assign(
    array (
        'name'  => 'Andy Fusniak',
        'website' => 'http://rd.zendvfr'
    )
);

// create mail object
$mail = new Zend_Mail('utf-8');

// render view
$bodyText = $html->render('template.phtml');

// configure base stuff
$mail->addTo('andyfusniak@gmail.com');
$mail->setSubject('Welcome to my test');
$mail->setFrom('info@holidaypropertyworldwide.com', 'HPW');
$mail->setBodyHtml($bodyText);
$mail->send();


