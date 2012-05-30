<?php
class Admin_Form_UrlRedirectForm extends Zend_Form
{
    protected $incomingUrl = null;
    protected $redirectUrl = null;
    protected $responseCode = null;
    protected $groupName = null;

    public function setIncomingUrl($incomingUrl)
    {
        $this->incomingUrl = $incomingUrl;
    }

    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
    }

    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;
    }

    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/admin/url-redirect/create');

        $this->addElement('text', 'incomingUrl',
            array(
                'required' => true,
                'value' => $this->incomingUrl
            )
        );

        $this->addElement('text', 'redirectUrl',
            array(
                'required' => true,
                'value' => $this->redirectUrl
            )
        );

        $this->addElement('select', 'responseCode',
            array(
                'required' => true,
                'value' => $this->responseCode,
                'multiOptions' => array(
                    '301 - Permanentaly Moved' => 301,
                    '302' => 302,
                    '404 - Missing Page' => 404
                )
            )
        );

        $this->addElement('text', 'groupName',
            array(
                'required' => true,
                'value' => $this->groupName
            )
        );
    }
}
