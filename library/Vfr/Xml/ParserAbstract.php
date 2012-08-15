<?php
abstract class Vfr_Xml_ParserAbstract
{
    /**
     * Singleton instance
     *
     * Marked only as protected to allow extension of the class. To extend,
     * simply override {@link getInstance()}.
     *
     * @var Hpw_XmlGeneratorAbstract
     */
    protected static $_instance = null;

    /**
     * @var DOMDocument the DOM tree
     */
    protected $_domDocument = null;

    /**
      * Enforce singleton; disallow cloning
      *
      * @return void
      */
    private function __clone()
    {
    }

    /**
     * Pretifies the XML so it's human readble
     *
     * @param string $xml the raw xml data
     * @param bool $addNewLines optionally adds extra new lines between element tags
     * @return string the tidy xml
     */
    private function _tidyXml($xml, $addNewlines = false)
    {
        //$xml = preg_replace("/(\s+)\<([a-zA-Z-]+)\>/", "\n$1<$2>", $xml);
        $xml = preg_replace("/( ){2}/", "    ", $xml);
        return $xml;
    }

    /**
     * Print the XML tree as plain text
     */
    public function printXml()
    {
        header("Content-Type: text/plain");

        $xml = $this->_tidyXml($this->_domDocument->saveXML());

        echo $xml;
        return $this;
    }

    public static function parse_error_handler($errno, $errstr, $errfile, $errline)
    {
        //var_dump(E_WARNING, $errno, $errstr, $errfile, $errline);
        if (($errno == E_WARNING) && (substr_count($errstr,"DOMDocument::load()") > 0)) {
            throw new DOMException($errstr);
        } else {
            return false;
        }
    }

    public function loadXml($xmlFile)
    {
        $this->_domDocument = new DOMDocument("1.0", "utf-8");


        set_error_handler(array("Vfr_Xml_ParserAbstract", "parse_error_handler"));

        try {
            return $this->_domDocument->load($xmlFile);
        } catch (DOMException $e) {
            $message = $e->getMessage();
            var_dump($message);
        }

        restore_error_handler();
    }
}