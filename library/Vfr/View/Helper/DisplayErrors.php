<?php
/**
 * Abstract class for extension
 */
require_once 'Zend/View/Helper/FormElement.php';

class Vfr_View_Helper_DisplayErrors extends Zend_View_Helper_FormElement
{
    const version = '1.0.0';

    /**
     * @var Zend_Form_Element
     */
    protected $_element;

    /**#@+
     * @var string Element block start/end tags and separator
     */
    protected $_htmlElementEnd       = '</li></ul>';
    protected $_htmlElementStart     = '<ul%s><li>';
    protected $_htmlElementSeparator = '</li><li>';
    /**#@-*/

    /**
     * Render form errors
     *
     * @param  string|array $errors Error(s) to render
     * @param  array $options
     * @return string
     */
    public function displayErrors($errors, array $options = null)
    {
        if (sizeof($errors) == 0) {
            return '';
        }

        $escape = true;
        if (isset($options['escape'])) {
            $escape = (bool) $options['escape'];
            unset($options['escape']);
        }

        if (empty($options['class'])) {
            $options['class'] = 'errors';
        }

        $start = $this->getElementStart();
        if (strstr($start, '%s')) {
            $attribs = $this->_htmlAttribs($options);
            $start   = sprintf($start, $attribs);
        }

        if ($escape) {
            foreach ($errors as $key => $error) {
                $errors[$key] = $this->view->escape($error);
            }
        }

        $html  = $start
               . implode($this->getElementSeparator(), (array) $errors)
               . $this->getElementEnd();

        return $html;
    }

    /**
     * Set end string for displaying errors
     *
     * @param  string $string
     * @return Zend_View_Helper_FormErrors
     */
    public function setElementEnd($string)
    {
        $this->_htmlElementEnd = (string) $string;
        return $this;
    }

    /**
     * Retrieve end string for displaying errors
     *
     * @return string
     */
    public function getElementEnd()
    {
        return $this->_htmlElementEnd;
    }

    /**
     * Set separator string for displaying errors
     *
     * @param  string $string
     * @return Zend_View_Helper_FormErrors
     */
    public function setElementSeparator($string)
    {
        $this->_htmlElementSeparator = (string) $string;
        return $this;
    }

    /**
     * Retrieve separator string for displaying errors
     *
     * @return string
     */
    public function getElementSeparator()
    {
        return $this->_htmlElementSeparator;
    }

    /**
     * Set start string for displaying errors
     *
     * @param  string $string
     * @return Zend_View_Helper_FormErrors
     */
    public function setElementStart($string)
    {
        $this->_htmlElementStart = (string) $string;
        return $this;
    }

    /**
     * Retrieve start string for displaying errors
     *
     * @return string
     */
    public function getElementStart()
    {
        return $this->_htmlElementStart;
    }

}
