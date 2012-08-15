<?php
class Admin_XmlLocationContentUploadController extends Zend_Controller_Action
{
    protected $_vfrConfig;

    public function init()
    {
        // get the destination from the configuration
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
        $this->_vfrConfig = $bootstrap['vfr'];
    }

    public function fileUploadAction()
    {
        $request = $this->getRequest();

        $form = new Admin_Form_XmlUploadForm();
        $form->setAction('/admin/xml-location-content-upload/file-upload');

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                try {
                    $fileElement = $form->getElement('filename');
                    $transferAdapter = $fileElement->getTransferAdapter();

                    $transferAdapter->setDestination(
                        $this->_vfrConfig['xml']['xml_files_dir']
                        . DIRECTORY_SEPARATOR
                        . $this->_vfrConfig['xml']['location_content_dirname']
                    );

                    $fileInfo = $transferAdapter->getFileInfo();

                    $targetFile = $fileInfo['filename']['name'];
                    $filterFileRename = new Zend_Filter_File_Rename(
                        array(
                            'target'    => $targetFile,
                            'overwrite' => true
                        )
                    );

                    $transferAdapter->addfilter($filterFileRename);
                    $transferAdapter->receive();

                    //  u   g    o
                    // -rw- -rw- r--
                    $outfile = $this->_vfrConfig['xml']['xml_files_dir']
                               . DIRECTORY_SEPARATOR
                               . $this->_vfrConfig['xml']['location_content_dirname']
                               . DIRECTORY_SEPARATOR
                               . $targetFile;

                    chmod($outfile, 0664);

                    // apply the file to the DB
                    $xmlLocationContentParser = new Vfr_Xml_LocationContentParser();
                    $loadStatus = $xmlLocationContentParser->loadXml($outfile);
                    $locationContentObj = $xmlLocationContentParser->createLocationContent();
                    $locationContentMapper = new Frontend_Model_LocationContentMapper();
                    $locationContentMapper->saveContentLocation($locationContentObj);

                    $this->_helper->redirector->gotoSimple(
                        'upload-confirm',
                        'xml-location-content-upload',
                        'admin'
                    );
                } catch (Zend_File_Transfer_Exception $e) {
                    die($e->getMessage());
                } catch (Exception $e) {
                    throw $e;
                }
            }
        }

        $this->view->assign(
            array(
                'form'  => $form
            )
        );
    }

    public function uploadConfirmAction() {}
}
