<?php
class Vfr_Mail
{
    const MODE_ONLY_HTML = 1;
    const MODE_ONLY_TXT = 2;
    const MODE_BOTH = 3;

    protected $_to;
    protected $_defaultFromEmail;
    protected $_defaultFromName;
    protected $_htmlTemplate;
    protected $_template;
    protected $_mail;

    private $_nonSecureUrl;
    private $_secureUrl;

    public function __construct($path, $template)
    {
        $this->_path = $path;
        $this->_template = $template;

        // get the the configuration
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();

        $this->_defaultFromEmail = $bootstrap['resources']['mail']['defaultFrom']['email'];
        $this->_defaultFromName  = $bootstrap['resources']['mail']['defaultFrom']['name'];


        // since the non secure and secure base URLs are often required for email templates
        // and since we've already loaded the bootstrap application.ini config above
        $this->_nonSecureUrl = $bootstrap['vfr']['website']['nonSecureUrl']; // e.g. http://www...com
        $this->_secureUrl    = $bootstrap['vfr']['website']['secureUrl']; // e.g. https://www...com

        // create mail object
        $this->_mail = new Zend_Mail('utf-8');
    }

    public function send($to, $subject, $params, $mode = self::MODE_BOTH)
    {
        if (null === $params)
            $params = array();

        // make the non secure and secure URL always available to template views
        $params['nonSecureUrl'] = $this->_nonSecureUrl;
        $params['secureUrl']    = $this->_secureUrl;

        $path = APPLICATION_PATH . $this->_path;

        // configure base stuff
        $this->_mail->addTo($to)
             ->setSubject($subject)
             ->setFrom($this->_defaultFromEmail, $this->_defaultFromName);

        if (($mode == self::MODE_ONLY_TXT) || ($mode == self::MODE_BOTH)) {
            $textFile  = $this->_template . '-txt.phtml';
            $textBody = null;
            if (file_exists($path . DIRECTORY_SEPARATOR . $textFile)) {
                $textView = new Zend_View();
                $textView->assign($params);
                $textView->setScriptPath($path);
                $textBody = $textView->render($textFile);
            }

            if ($textBody)
                $this->_mail->setBodyText($textBody);
        }

        if (($mode == self::MODE_ONLY_HTML) || ($mode == self::MODE_BOTH)) {
            $htmlFile = $this->_template . '-html.phtml';
            $htmlBody = null;
            if (file_exists($path . DIRECTORY_SEPARATOR . $htmlFile)) {
                $htmlView = new Zend_View();
                $htmlView->assign($params);
                $htmlView->setScriptPath($path);
                $htmlBody = $htmlView->render($htmlFile);
            }

            if ($htmlBody)
                $this->_mail->setBodyHtml($htmlBody);
        }

        // send the email using the default Zend_Sendmail transport (i.e. postfix)
        if (APPLICATION_ENV == 'mars') {
            var_dump($this->_mail);
            //die();
            $this->_mail->send();
        } else {
            $this->_mail->send();
        }
    }
}
