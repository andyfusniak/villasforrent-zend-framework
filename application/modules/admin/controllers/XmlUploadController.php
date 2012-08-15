<?php
class Admin_XmlUploadController extends Zend_Controller_Action
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
        $form->setAction('/admin/xml-upload/file-upload');

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                try {
                    $fileElement = $form->getElement('filename');
                    $transferAdapter = $fileElement->getTransferAdapter();

                    $transferAdapter->setDestination(
                        $this->_vfrConfig['xml']['xml_files_dir']
                    );

                    $fileInfo = $transferAdapter->getFileInfo();

                    $filterFileRename = new Zend_Filter_File_Rename(
                        array(
                            'target'    => $this->_vfrConfig['xml']['xml_upload_filename'],
                            'overwrite' => true
                        )
                    );

                    $transferAdapter->addfilter($filterFileRename);
                    $transferAdapter->receive();

                    //  u   g    o
                    // -rw- -rw- r--
                    chmod($fileInfo['filename']['destination']
                          . DIRECTORY_SEPARATOR
                          . $this->_vfrConfig['xml']['xml_upload_filename'],
                          0664
                    );

                    $this->_helper->redirector->gotoSimple(
                        'upload-confirm',
                        'xml-upload',
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
