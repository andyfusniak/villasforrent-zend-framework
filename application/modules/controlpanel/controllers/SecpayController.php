<?php
class Controlpanel_SecpayController extends Zend_Controller_Action
{
    protected $_vfrConfig;

    public function init()
    {
        // ensure the advertiser is logged in
        if (!Vfr_Auth_Advertiser::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoSimple(
                'login',
                'authentication',
                'controlpanel'
            );
        }

        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
        $this->_vfrConfig = $bootstrap['vfr'];
    }

    public function testAction()
    {
        $url = $this->_vfrConfig['secpay']['apiUrl']
             . '?merchant=' . urlencode($this->_vfrConfig['secpay']['merchant'])
             . '&trans_id=12345678'
             . '&amount=15.00'
             . '&template=' . urlencode($this->_vfrConfig['secpay']['template'])
             . '&ssl_cb=true'
             . '&cb_post=true'
             . '&backcallback=' . urlencode($this->_vfrConfig['secpay']['backcallback'])
             . '&callback=' . urlencode($this->_vfrConfig['secpay']['callback']);
        $this->view->assign(
            array(
                'url' => $url,
                'merchant'  => $this->_vfrConfig['secpay']['merchant'],
                'template'  => $this->_vfrConfig['secpay']['template'],
                'trans_id'  => '12345',
                'apiUrl'    => $this->_vfrConfig['secpay']['apiUrl'],
                'callback'  => $this->_vfrConfig['secpay']['callback']
            )
        );
    }
}
