<?php
/**
 * @author Andrew Fusniak <andy@greycatmedia.co.uk>
 * @version 1.0.0
 * @copyright Copyright (c) 2012, Andrew Fusniak
 * @package Controlpanel_OauthController
 */
class Controlpanel_OauthController extends Zend_Controller_Action
{
    protected $_oauth;

    /**
     * @var string Controller version string
     */
    const version = '1.0.0';

    public function preDispatch()
    {
        $this->_helper->ensureSecure();
    }

    /**
     * Initialisation of controller
     */
    public function init()
    {
        // The OAuth dialog box is intended for 3rd party apps
        // and requires no layout
        $this->_helper->layout->disableLayout();

        $this->_oauth = new Vfr_OAuth_OAuth();
    }

    private function jsonResponse($code, $data)
    {
        $this->_helper->viewRenderer->setNoRender(true);

        $response = $this->getResponse();
        $response->setHttpResponseCode($code);
        $response->setHeader('Content-Type', 'application/json');
        $response->setHeader('Cache-Control', 'no-store');

        $json = Zend_Json::encode($data);
        echo Zend_Json::prettyPrint(
            $json,
            array ('indent' => '    ')
        );
    }

    public function tokenAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            $data = array (
                'error' => 'invalid_request',
                'error_description' => "The request must be of type POST and made using an HTTPS connection."
            );

            $this->jsonResponse(
                400, // Bad request
                $data
            );

            return;
        }

        $grantType    = $request->getParam('grant_type');
        $clientId     = $request->getParam('client_id');
        $clientSecret = $request->getParam('client_secret');
        $code         = $request->getParam('code');
        $redirectUri  = $request->getParam('redirect_uri');

        if (($grantType == null) || ($clientId == null) || ($clientSecret == null) || ($code == null) || ($redirectUri == null)) {
            $data = array (
                'error' => 'invalid_request',
                'error_description' => "The request is missing a required parameter, includes an unsupported parameter value (other than grant type), repeats a parameter, includes multiple credentials, utilizes more than one mechanism for authenticating the client, or is otherwise malformed."
            );

            $this->jsonResponse(
                400, // HTTP 400 (Bad Request)
                $data
            );

            return;
        } else if ('authorization_code' != $grantType) {
            $data = array (
                'error' => 'unsupported_grant_type',
                'error_description' => "The authorization grant type is not supported by the authorization server.  Expected 'authorization_code'."
            );

            $this->jsonResponse(
                400, // HTTP 400 (Bad Request)
                $data
            );

            return;
        }

        // check the grant is valid
        $oAuthGrantModel = new Common_Model_OAuthAuthorization();
        if (!$oAuthGrantModel->hasValidGrant($clientId, $code)) {
            $data = array (
                'error' => 'invalid_grant',
                'error_description' => "The provided authorization grant (e.g. authorization code, resource owner credentials) or refresh token is invalid, expired, revoked, does not match the redirection URI used in the authorization request, or was issued to another client."
            );

            $this->jsonResponse(
                400,
                $data
            );

            return;
        }

        // check the client id and secret matches
        $oAuthResourceModel = new Common_Model_OAuthResource();
        if (!$oAuthResourceModel->checkClientSecretKey($clientId, $clientSecret)) {
            $data = array (
                'error' => 'unauthorized_client',
                'error_description' => "The authenticated client is not authorized to use this authorization grant type."
            );

            $this->jsonResponse(
                400, // Bad Request
                $data
            );

            return;
        }

        // check the redirect uri matches
        if (!$oAuthResourceModel->checkClientIdMatchesRedirectUri($clientId, $redirectUri)) {
            $data = array (
                'error' => 'invalid_grant',
                'error_description' => "The provided authorization grant (e.g. authorization code, resource owner credentials) or refresh token is invalid, expired, revoked, does not match the redirection URI used in the authorization request, or was issued to another client. Check your client_id is correct and that your redirect_uri matches the application settings in your accounnt control panel."
            );

            $this->jsonResponse(
                400, // Bad Request
                $data
            );

            return;
        }

        die('working so far');
        // isValidGrantType($grantType)
    }

    /**
     * Action for authorisation dialogue
     */
    public function authAction()
    {
        $request = $this->getRequest();
        $responseType = $request->getParam('response_type');
        $clientId     = $request->getParam('client_id');
        $redirectUri  = $request->getParam('redirect_uri');
        $scope        = $request->getParam('scope');
        $state        = $request->getParam('state');


        //var_dump($responseType, $clientId, $redirectUri, $scope, $state);
        //die();

        // the response_type, client_id and redirect_uri are all required
        // query parameters for the authorisation request
        if (($responseType == null) || ($clientId == null) || ($redirectUri == null)) {
            $data = array (
                'error' => 'invalid_request',
                'error_description' => "The request is missing a required parameter, includes an invalid parameter value, or is otherwise malformed. response_type, client_id and redirect_uri are mandatory parameters."
            );

            $this->jsonResponse(401, $data);
            return;
        }

        // check the response_type is a support type
        if (!$this->_oauth->isValidResponseType($responseType)) {
            $data = array (
                'error' => 'unsupported_response_type',
                'error_description' => "The authorization server does not support obtaining an authorization code using this method."
            );

            $this->jsonResponse(401, $data);
            return;
        }

        // check the client_id and redirect_uri match
        $oAuthResourceModel = new Common_Model_OAuthResource();
        if (!$oAuthResourceModel->checkClientIdMatchesRedirectUri($clientId, $redirectUri)) {
            $data = array (
                'error' => 'unauthorized_client',
                'error_description' => "The client is not authorized to request an authorization code using this method.  Check your client_id is correct and that your redirect_uri matches the application settings in your accounnt control panel."
            );

            $this->jsonResponse(401, $data);
        }

        $this->_helper->ensureLoggedIn(true);

        // create the form and set the hidden form element
        $form = new Controlpanel_Form_OAuth_Authorise(
            array(
                'responseType' => $responseType,
                'clientId'     => $clientId,
                'redirectUri'  => $redirectUri,
                'scope'        => $scope,
                'state'        => $state
            )
        );

        $request = $this->getRequest();
        if ($request->isPost()) {
            $allow = (bool) $request->getParam('allow', 0);

            if ($allow == 1) {
                // generate a new authorization grant
                $oAuthAuthorizationModel = new Common_Model_OAuthAuthorization();
                $code = $oAuthAuthorizationModel->addAuthorizationGrant($clientId, $redirectUri);

                $redirector = $this->_helper->getHelper('Redirector');

                $url = $redirectUri . "?code=" . urlencode($code) . "&state=" . urlencode($state);

                // set a 302 Found and redirect to the client app
                $redirector->setCode(302)
                           ->gotoUrl($url);
            }
        }

        $this->view->assign(
            array(
                'form' => $form
            )
        );
    }
}
