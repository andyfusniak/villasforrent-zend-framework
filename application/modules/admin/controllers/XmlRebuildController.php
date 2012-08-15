<?php
class Admin_XmlRebuildController extends Zend_Controller_Action
{
    protected $_vfrConfig = null;
    protected $_xmlDumpFile = null;
    protected $_xmlUploadFile = null;

    public function init()
    {
        // get the destination from the configuration
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
        $this->_vfrConfig = $bootstrap['vfr'];

        $xmlFilesDir = $this->_vfrConfig['xml']['xml_files_dir'];
        $this->_xmlDumpFile = $xmlFilesDir
                            . DIRECTORY_SEPARATOR
                            . $this->_vfrConfig['xml']['xml_dump_filename'];

        $this->_xmlUploadFile = $xmlFilesDir
                              . DIRECTORY_SEPARATOR
                              . $this->_vfrConfig['xml']['xml_upload_filename'];
    }

    public function overviewAction()
    {
        if (file_exists($this->_xmlDumpFile)) {
            $dumpFileExists = true;
            $dumpMd5Hash   = hash_file('md5', $this->_xmlDumpFile);
            $statsXmlDumpFile   = stat($this->_xmlDumpFile);
            $dumpXmlContent = file_get_contents($this->_xmlDumpFile);
            $dumpNumLines = substr_count($dumpXmlContent, "\n");
        } else {
            $dumpFileExists = false;
            $dumpMd5Hash = null;
            $dumpXmlContent = "";
            $dumpNumLines = 0;
        }

        if (file_exists($this->_xmlUploadFile)) {
            $uploadFileExists = true;
            $uploadMd5Hash = hash_file('md5', $this->_xmlUploadFile);
            $statsXmlUploadFile = stat($this->_xmlUploadFile);
            $uploadXmlContent = file_get_contents($this->_xmlUploadFile);
            $uploadNumLines = substr_count($uploadXmlContent, "\n");
        } else {
            $uploadFileExists = false;
            $uploadMd5Hash = null;
            $uploadXmlContent = "";
            $uploadNumLines = 0;
        }

        // 0    dev     device number
        // 1    ino     inode number *
        // 2    mode    inode protection mode
        // 3    nlink   number of links
        // 4    uid     userid of owner *
        // 5    gid     groupid of owner *
        // 6    rdev    device type, if inode device
        // 7    size    size in bytes
        // 8    atime   time of last access (Unix timestamp)
        // 9    mtime   time of last modification (Unix timestamp)
        // 10   ctime   time of last inode change (Unix timestamp)
        // 11   blksize blocksize of filesystem IO **
        // 12   blocks  number of 512-byte blocks allocated **
        //
        // * On Windows this will always be 0.
        // Only valid on systems supporting the st_blksize type - other systems (e.g. Windows) return -1.

        $this->view->assign(
            array(
                'dumpFile'    => basename($this->_xmlDumpFile),
                'dumpMd5Hash' => $dumpMd5Hash,
                'dumpSize'    => isset($statsXmlDumpFile[7]) ? $statsXmlDumpFile[7] : null, // size
                'dumpMtime'   => isset($statsXmlDumpFile[9]) ? $statsXmlDumpFile[9] : null, // mtime
                'dumpFileExists' => $dumpFileExists,
                'dumpXmlContent' => $dumpXmlContent,
                'dumpNumLines'   => $dumpNumLines,

                'uploadFile'    => basename($this->_xmlUploadFile),
                'uploadMd5Hash' => $uploadMd5Hash,
                'uploadSize'    => isset($statsXmlUploadFile[7]) ? $statsXmlUploadFile[7] : null, // size
                'uploadMtime'   => isset($statsXmlUploadFile[9]) ? $statsXmlUploadFile[9] : null, // mtime
                'uploadFileExists' => $uploadFileExists,
                'uploadXmlContent' => $uploadXmlContent,
                'uploadNumLines'   => $uploadNumLines,

                'sameFiles' => ($dumpMd5Hash === $uploadMd5Hash) ? true : false
            )
        );
    }

    private function _xmlDownloadHeaders($filename)
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        header('Content-disposition: attachment; filename="' . $filename . '"');
        header("Content-Type: application/force-download");
        header("Content-Transfer-Encoding: binary");
        header("Pragma: no-cache");
        header("Expires: 0");
    }

    public function downloadXmlDumpFileAction()
    {
        $this->_xmlDownloadHeaders(basename($this->_xmlDumpFile));

        readfile($this->_xmlDumpFile);
    }

    public function downloadXmlUploadFileAction()
    {
        $this->_xmlDownloadHeaders(basename($this->_xmlUploadFile));

        readfile($this->_xmlUploadFile);
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

    public function parseXmlUploadAction()
    {
        $domDocument = new DOMDocument("1.0", "utf-8");
        //$domDocument->formatOutput = true;
        $domDocument->preserveWhiteSpace = false;

        set_error_handler(array("Admin_XmlRebuildController", "parse_error_handler"));

        try {
            $domDocument->load($this->_xmlUploadFile);
        } catch (DOMException $e) {
            $message = $e->getMessage();
        }

        restore_error_handler();

        $this->view->assign(
            array(
                'message' => isset($message) ? $message : null
            )
        );
    }

    public function dryRunXmlUploadAction()
    {
        $domDocument = new DOMDocument("1.0", "utf-8");
        //$domDocument->formatOutput = true;
        $domDocument->preserveWhiteSpace = false;

        set_error_handler(array("Admin_XmlRebuildController", "parse_error_handler"));

        try {
            $domDocument->load($this->_xmlUploadFile);
            $rootNode = $domDocument->documentElement;
        } catch (DOMException $e) {
            throw $e;
        }

        restore_error_handler();

        $locationModel = new Common_Model_Location();
        $locationModel->tagXmlDomTree($rootNode);

        echo "<hr />";
        $nestedSet = $locationModel->nestedSetFromTaggedXmlTree($rootNode);

        $this->view->assign(
            array(
                'nestedSet' => $nestedSet
            )
        );
    }

    public function dbRebuildAction()
    {
        $locationModel = new Common_Model_Location();

        // purse the Locations table and the FeaturedProperties table
        // will purge since it's delete is set to cascade
        // The idLocation in the Properties table will set to NULL
        $locationModel->purgeLocationsTable();

        $domDocument = new DOMDocument("1.0", "utf-8");
        //$domDocument->formatOutput = true;
        $domDocument->preserveWhiteSpace = false;

        set_error_handler(array("Admin_XmlRebuildController", "parse_error_handler"));

        try {
            $domDocument->load($this->_xmlUploadFile);
            $rootNode = $domDocument->documentElement;
        } catch (DOMException $e) {
            throw $e;
        }

        // tag the hierarchy tree and then generated the nested set structure
        $locationModel->tagXmlDomTree($rootNode);
        $nestedSet = $locationModel->nestedSetFromTaggedXmlTree($rootNode);

        // rebuild the database using the nestedset

        $locationModel->rebuildDbFromNestedSet($nestedSet);
    }
}
