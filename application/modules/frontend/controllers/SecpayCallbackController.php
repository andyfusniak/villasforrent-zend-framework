<?php
class SecpayCallbackController extends Zend_Controller_Action
{
    public function init() {}

    public function callbackAction()
    {
        $request = $this->getRequest();

        $this->view->assign(
            array(
                'params' => $request->getParams()
            )
        );
    }
}
