<?php
class Admin_XmlParseLocationContentController extends Zend_Controller_Action
{
    protected $_vfrConfig = null;
    protected $_locationContentDir = null;

    public function init()
    {
        // get the destination from the configuration
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
        $this->_vfrConfig = $bootstrap['vfr'];

        $xmlFilesDir = $this->_vfrConfig['xml']['xml_files_dir'];
        $locationContentDirname = $this->_vfrConfig['xml']['location_content_dirname'];

        $this->_locationContentDir = $xmlFilesDir . DIRECTORY_SEPARATOR . $locationContentDirname;
    }

    public function reloadAction()
    {
        $request = $this->getRequest();

        $filename = $request->getParam('filename');
        $filepath = $this->_locationContentDir . DIRECTORY_SEPARATOR . $filename;

        if (!file_exists($filepath)) {
            $this->_helper->redirector->gotoSimpleAndExit(
                'file-not-found',
                'xml-parse-location-content',
                'admin'
            );
        }

        $xmlLocationContentParser = new Vfr_Xml_LocationContentParser();
        $loadStatus = $xmlLocationContentParser->loadXml($filepath);

        //$xmlLocationContentParser->printXml();

        $locationContentObj = $xmlLocationContentParser->createLocationContent();


        $locationContentMapper = new Frontend_Model_LocationContentMapper();
        $locationContentMapper->saveContentLocation($locationContentObj);


        //var_dump($locationContentObj);
    }

    public function fileNotFoundAction() {}
}
