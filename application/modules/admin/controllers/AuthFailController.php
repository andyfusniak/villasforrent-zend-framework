<?php
class Admin_AuthFailController extends Zend_Controller_Action
{
    const version = '1.0.0';

    public function init()
    {
        $this->_helper->layout->disableLayout();
    }

    public function authFailAction() {}
}
