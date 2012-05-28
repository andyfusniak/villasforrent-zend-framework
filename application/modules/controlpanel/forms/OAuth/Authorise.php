<?php
class Controlpanel_Form_OAuth_Authorise extends Zend_Form
{
    protected $responseType;
    protected $clientId;
    protected $redirectUri;
    protected $scope;
    protected $state;

    public function setResponseType($responseType)
    {
        $this->responseType = $responseType;
    }

    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }

    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/controlpanel/oauth/auth');

        $this->addElement('hidden', 'responseType', array(
            'value' => $this->responseType
        ));

        $this->addElement('hidden', 'clientId', array(
            'value' => $this->clientId
        ));

        $this->addElement('hidden', 'redirectUri', array(
            'value' => $this->redirectUri
        ));

        $this->addElement('hidden', 'scope', array(
            'value' => $this->scope
        ));

        $this->addElement('hidden', 'state', array(
            'value' => $this->state
        ));
    }
}
