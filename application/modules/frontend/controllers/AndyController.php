<?php
class AndyController extends Zend_Controller_Action
{
    public function init()
    {
        
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('list', 'json')
                    ->addActionContext('modify', 'html')
                    ->initContext();
    }

    public function listAction()
    {
        // pretend this is a sophisticated DB query
        $data = array (
            'red',
            'green',
            'blue',
            'yellow'
        );

        $request = $this->getRequest();
        $acceptHeader = $request->getHeader('Accept');
        
        $this->view->assign(
            array (
                'data' => $data
            )
        );
    }

    public function indexAction()
    {
        ZendX_JQuery::enableView($this->view);
        $jquery = $this->view->jQuery();
        $jquery->enable();
			   //->uiEnable();
    }
}
