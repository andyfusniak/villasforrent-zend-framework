<?php
class Admin_SystemCheckController extends Zend_Controller_Action
{
    const version = '1.0.0';

    public function doChecksAction()
    {
        // get the application config from the bootstrap
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
        $vfrConfig = $bootstrap['vfr'];

        // directory paths (relative to the application path)
        $dataDir  = APPLICATION_PATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data';
        $cacheDir = $dataDir . DIRECTORY_SEPARATOR . 'cache';
        $logDir   = $dataDir . DIRECTORY_SEPARATOR . 'logs';

        // PHP - APC
        $phpApcSettings = array(
            'realpath_cache_size'    => ini_get('realpath_cache_size'),
            'realpath_cache_ttl'     => ini_get('realpath_cache_ttl')
        );

        // strip the K off the end of the value
        // then check it's at least 128 K in size
        $size = substr(
            $phpApcSettings['realpath_cache_size'],
            0,
            strlen($phpApcSettings['realpath_cache_size']) - 1
        );

        // make sure the real cache size is at least 128K
        $phpApcSettings['realpath_cache_size_ok'] = ($size >= 128) ? 1 : 0;

        // make sure the realpath cache TTL is at least 10 mins
        $phpApcSettings['realpath_cache_ttl_ok']  = ($phpApcSettings['realpath_cache_ttl'] >= 600) ? 1 : 0;

        $fileUploadPhpSettings = array(
            'file_uploads'        => (int) ini_get('file_uploads'),
            'max_file_uploads'    => ini_get('max_file_uploads'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'upload_tmp_dir'      => ini_get('upload_tmp_dir')
        );

        $files = array (
            'dataDir' => array(
                'path' => $dataDir,
                'name' => 'data'
            ),

            'cacheDir' => array(
                'path' => $cacheDir,
                'name' => 'cache'
            ),

            'logDir' => array(
                'path' => $logDir,
                'name' => 'data/logs'
            ),

            'sessionDir'=> array(
                'path' => $dataDir . DIRECTORY_SEPARATOR . 'session',
                'name' => 'data/session'
            ),

            'originalImagesDir' => array(
                'path' => $dataDir . DIRECTORY_SEPARATOR . 'images_originals',
                'name' => 'data/images_originals'
            ),

            'xmlFilesDir' => array(
                'path' => $dataDir . DIRECTORY_SEPARATOR . 'xml_files',
                'name' => 'data/xml_files'
            ),

            'appLogFile' => array(
                'path' => $logDir  . DIRECTORY_SEPARATOR . 'application.log',
                'name' => 'data/logs/application.log'
            ),

            'dynamicImagesDir' => array(
                'path' => APPLICATION_PATH . DIRECTORY_SEPARATOR
                          . '..' . DIRECTORY_SEPARATOR
                          . 'public' . DIRECTORY_SEPARATOR . 'photos',
                'name' => 'public/photos'
            ),

            'upload_tmp_dir' => array(
                'path' => $fileUploadPhpSettings['upload_tmp_dir'],
                'name' => 'upload_tmp_dir'
            )
        );

        $permissions = array();
        foreach ($files as $name=>$value) {
            $permissions[$name]['r'] = is_readable($value['path']);
            $permissions[$name]['w'] = is_writable($value['path']);
            $permissions[$name]['x'] = is_executable($value['path']);
        }

        //
        // PHP checks
        //

        // gd library supported?
        $phpGdInstalled = function_exists('gd_info');
        if ($phpGdInstalled)
            $gdInfo = gd_info();

        // mcrypt library supported?
        $phpMcryptInstalled = function_exists('mcrypt_list_modes');

        // mysqli library supported?
        $phpMysqliInstalled = function_exists('mysqli_connect');

        // session library supported?
        $phpSessionInstalled = function_exists('session_start');

        // curl library supported?
        $phpCurlInstalled = function_exists('curl_init');

        // Alternative PHP Cache
        $phpApcInstalled = function_exists('apc_cache_info');

        // Mysql triggers
        $systemModel = new Common_Model_System();
        $triggerRowInsert = $systemModel->getInformationSchemaTriggerByName('content_checksum_insert');
        $triggerRowUpdate = $systemModel->getInformationSchemaTriggerByName('content_checksum_update');

        //var_dump($fileUploadPhpSettings);
        //die();

        $this->view->assign(
            array(
                'phpGdInstalled'      => $phpGdInstalled,
                'phpMcryptInstalled'  => $phpMcryptInstalled,
                'phpMysqliInstalled'  => $phpMysqliInstalled,
                'phpSessionInstalled' => $phpSessionInstalled,
                'phpCurlInstalled'    => $phpCurlInstalled,
                'phpApcInstalled'     => $phpApcInstalled,
                'triggerChecksumI'    => $triggerRowInsert instanceof Common_Resource_Trigger_Row ? true : false,
                'triggerChecksumU'    => $triggerRowUpdate instanceof Common_Resource_Trigger_Row ? true : false,
                'gdInfo'              => isset($gdInfo) ? $gdInfo : null,
                'vfrConfig'           => $vfrConfig,
                'dataDir'             => $dataDir,
                'cacheDir'            => $cacheDir,
                'logDir'              => $files['logDir']['path'],
                'sessionDir'          => $files['sessionDir']['path'],
                'originalImagesDir'   => $files['originalImagesDir']['path'],
                'xmlFilesDir'         => $files['xmlFilesDir']['path'],
                'appLogFile'          => $files['appLogFile']['path'],
                'dynamicImagesDir'    => $files['dynamicImagesDir']['path'],
                'uploadTmpDir'        => $files['upload_tmp_dir']['path'],
                'permissions'         => $permissions,

                // php upload settings
                'fileUploadPhpSettings' => $fileUploadPhpSettings,

                // php APC settings
                'phpApcSettings' => $phpApcSettings
            )
        );



    }

    public function phpInfoAction() {}
}
