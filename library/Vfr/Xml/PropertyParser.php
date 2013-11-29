<?php
class Vfr_Xml_PropertyParser
{
    /**
     * @var DOMDocument
     */
    protected $_domDocument = null;

    /**
     * @var string
     */
    protected $_xmlFile = null;

    public static function parse_error_handler($errno, $errstr, $errfile, $errline)
    {
        //var_dump(E_WARNING, $errno, $errstr, $errfile, $errline);
        if (($errno == E_WARNING) && (substr_count($errstr,"DOMDocument::load()") > 0)) {
            throw new DOMException($errstr);
        } else {
            return false;
        }
    }

    public function __construct($xmlFile)
    {
        $this->_xmlFile = $xmlFile;
        $this->_domDocument = new DOMDocument("1.0", "utf-8");

        set_error_handler(array("Vfr_Xml_PropertyParser", "parse_error_handler"));

        try {
            $this->_domDocument->load($this->_xmlUploadFile);
        } catch (DOMException $e) {
            $message = $e->getMessage();
        }

        restore_error_handler();
    }

    /**
     * Returns an object tree model of the XML
     * @return array a structure of objects representing the XML file
     */
    public function getObjectStructure()
    {
        $propertyElement = $this->_domDocument->getElementById('property');

        var_dump($propertyElement);

    }
}
