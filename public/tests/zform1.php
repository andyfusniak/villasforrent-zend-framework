<?php
require_once 'Zend/Loader.php';
//Zend_Loader::registerAutoload();

require_once 'Zend/View.php';
require_once 'Zend/Form.php';

$view = new Zend_View();
$form = new Zend_Form();
$form->setAction('login');
$form->setMethod('post');
$form->addElement('text', 'username', array(
'label' => 'Username:',
));
$form->addElement('password', 'password', array(
'label' => 'Password:',
));
$form->addElement('submit', 'submit', array(
'label' => 'Login',
'ignore' => true
));
echo $form->render($view);

