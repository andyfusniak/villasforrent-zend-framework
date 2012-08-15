<?php
class Frontend_Model_Secpay
{
    private $_idSecpay;
    private $valid;
    private $transId;
    private $code;
    private $authCode;
    private $amount;
    private $respCode;
    private $message;
    private $ip;
    private $testStatus;
    private $cv2avs;
    private $backcallback;
    private $mpiStatusCode;
    private $mpiMessage;
    private $hash;
    private $expiry;
    private $cardNo;
    private $customer;
    private $currency;
    private $cardType;
    private $_added;

    public function __construct() {}

    public function setSecpayId($idSecpay)
    {
        $this->_idSecpay = $idSecpay;
        return $this;
    }

    public function getSecpayId()
    {
        return $this->_idSecpay;
    }

    public function setValid($valid)
    {
        $this->valid = $valid;
        return $this;
    }

    public function getValid()
    {
        return $this->valid;
    }

    public function setTransId($transId)
    {
        $this->transId = $transId;
        return $this;
    }

    public function getTransId()
    {
        return $this->transId;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setAuthCode($authCode)
    {
        $this->authCode = $authCode;
    }

    public function getAuthCode()
    {
        return $this->authCode;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setRespCode($respCode)
    {
        $this->respCode = $respCode;
        return $this;
    }

    public function getRespCode()
    {
        return $this->respCode;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function setTestStatus($testStatus)
    {
        $this->testStatus = $testStatus;
        return $this;
    }

    public function getTestStatus()
    {
        return $this->testStatus;
    }

    public function setCv2Qvs($cv2avs)
    {
        $this->cv2avs = $cv2avs;
        return $this;
    }

    public function getCv2Avs()
    {
        return $this->cv2avs;
    }

    public function setBackcallback($backcallback)
    {
        $this->backcallback = $backcallback;
        return $this;
    }

    public function getBackcallback()
    {
        return $this->backcallback;
    }

    public function setMpiStatusCode($mpiStatusCode)
    {
        $this->mpiStatusCode = $mpiStatusCode;
        return $this;
    }

    public function getMpiStatusCode()
    {
        return $this->mpiStatusCode;
    }

    public function setMpiMessage($mpiMessage)
    {
        $this->mpiMessage = $mpiMessage;
        return $this;
    }

    public function getMpiMessage()
    {
        return $this->mpiMessage;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setExpiry($expiry)
    {
        $this->expiry = $expiry;
        return $this;
    }

    public function getExpiry()
    {
        return $this->expiry;
    }

    public function setCardNo($cardNo)
    {
        $this->cardNo = $cardNo;
        return $this;
    }

    public function getCardNo()
    {
        return $this->cardNo;
    }

    public function setCustomer($customer)
    {
        $this->customer = $customer;
        return $this;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCardType($cardType)
    {
        $this->cardType = $cardType;
        return $this;
    }

    public function getCardType()
    {
        return $this->cardType;
    }

    public function setAdded($added)
    {
        $this->_added = $added;
        return $this;
    }

    public function getAdded()
    {
        return $this->added;
    }
}
