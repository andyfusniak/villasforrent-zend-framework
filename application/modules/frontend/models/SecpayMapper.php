<?php
class Frontend_Model_SecpayMapper extends Frontend_Model_MapperAbstract
{
    public function addSecpay(Frontend_Model_Secpay $secpayObj)
    {
        $secpayResource = $this->getResource('Secpay');

        $params = array(
            'valid'         => $secpayObj->getValid(),
            'transId'       => $secpayObj->getTransId(),
            'code'          => $secpayObj->getCode(),
            'authCode'      => $secpayObj->getAuthCode(),
            'amount'        => $secpayObj->getAmount(),
            'respCode'      => $secpayObj->getRespCode(),
            'message'       => $secpayObj->getMessage(),
            'ip'            => $secpayObj->getIp(),
            'testStatus'    => $secpayObj->getTestStatus(),
            'cv2avs'        => $secpayObj->getCv2Avs(),
            'backcallback'  => $secpayObj->getBackcallback(),
            'mpiStatusCode' => $secpayObj->getMpiStatusCode(),
            'mpiMessage'    => $secpayObj->getMpiMessage(),
            'hash'          => $secpayObj->getHash(),
            'expiry'        => $secpayObj->getExpiry(),
            'cardNo'        => $secpayObj->getCardNo(),
            'customer'      => $secpayObj->getCustomer(),
            'currency'      => $secpayObj->getCurrency(),
            'cardType'      => $secpayObj->getCardType()
        );

        $idSecpay = $secpayResource->addSecpay($params);

        return $idSecpay;
    }
}
