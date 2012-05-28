<?php
set_include_path('../library' . PATH_SEPARATOR . get_include_path());

require_once 'Zend/Controller/Front.php';    
require_once 'Zend/Controller/Router/Route.php';    

$ctrl  = Zend_Controller_Front::getInstance();
$router = $ctrl->getRouter();

$route = new Zend_Controller_Router_Route(
    'user/:username',
        array(
                'controller' => 'user',
                        'action'     => 'info'
                            )
        );
$router->addRoute('user', $route);

$ctrl->run('../controllers');
