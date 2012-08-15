<?php
class SecpayCallbackController extends Zend_Controller_Action
{
    public function init() {}

    public function callbackAction()
    {
        $request = $this->getRequest();

        $params = array(
            'valid'         => $request->getParam('valid', null),
            'transId'       => $request->getParam('trans_id', null),
            'code'          => $request->getParam('code', null),
            'authCode'      => $request->getParam('auth_code', null),
            'amount'        => $request->getParam('amount', null),
            'respCode'      => $request->getParam('resp_code', null),
            'message'       => $request->getParam('message', null),
            'ip'            => $request->getParam('ip', null),
            'testStatus'    => $request->getParam('test_status', null),
            'cv2avs'        => $request->getParam('cv2avs', null),
            'backcallback'  => $request->getParam('backcallback', null),
            'mpiStatusCode' => $request->getParam('mpi_status_code', null),
            'mpiMessage'    => $request->getParam('mpi_message', null),
            'hash'          => $request->getParam('hash', null),
            'expiry'        => $request->getParam('expiry', null),
            'cardNo'        => $request->getParam('card_no', null),
            'customer'      => $request->getParam('customer', null),
            'currency'      => $request->getParam('currency', null),
            'cardType'      => $request->getParam('card_type', null)
        );

        $this->view->assign(
            array(
                'params' => $request->getParams()
            )
        );
    }
}
