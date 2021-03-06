<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initPluginLoaderCache()
    {
        if ('live' == $this->getEnvironment()) {
            $classFileIncCache = APPLICATION_PATH . '/../data/cache/pluginLoaderCache.php';
            if (file_exists($classFileIncCache)) {
                include_once $classFileIncCache;
            }

            Zend_Loader_PluginLoader::setIncludeFileCache(
                $classFileIncCache
            );
        }
    }

    protected function _initDbCaches()
    {
        if ('live' == $this->getEnvironment()) {
            $frontendOptions = array (
                'automatic_serialization' => true
            );

            try {
                $cache = Zend_Cache::factory('Core',
                    'Apc',
                    $frontendOptions
                );
            } catch (Zend_Cache_Exception $e) {
                var_dump($e);
                die();
            }

            Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
        }
    }

    protected function _initAutoload()
    {
        $autoLoader = new Zend_Application_Module_Autoloader(
            array(
                'namespace' => 'Common',
                'basePath' => APPLICATION_PATH . '/modules/common'));

        $autoLoader->addResourceTypes(
            array(
                'modelResource' => array (
                    'path'      => 'models/resources',
                    'namespace' => 'Resource',
                )
            )
        );

        $autoLoader = new Zend_Application_Module_Autoloader(
            array (
                'namespace' => 'Admin',
                'basePath'  => APPLICATION_PATH . '/modules/admin'
            )
        );

        $autoLoader = new Zend_Application_Module_Autoloader(
            array (
                'namespace' => 'Controlpanel',
                'basePath'  => APPLICATION_PATH . '/modules/controlpanel'
            )
        );

        $autoLoader = new Zend_Application_Module_Autoloader(
            array (
                'namespace' => 'Frontend',
                'basePath'  => APPLICATION_PATH . '/modules/frontend'
            )
        );

        $autoLoader = new Zend_Application_Module_Autoloader(
            array (
                'namespace' => 'Api',
                'basePath'  => APPLICATION_PATH . '/modules/api'
            )
        );

        //$loader = Zend_Loader_Autoloader::getInstance();
        //$loader->registerNamespace('Vfr_');
        //$loader->setFallbackAutoloader(true);
        return $autoLoader;
    }

    protected function _initLogging()
    {
        // Firebug Logging Styles
        // Style        Description
        // LOGi         Displays a plain log message
        // INFO         Displays an info log message
        // WARN         Displays a warning log message
        // ERROR        Displays an error log message that increments Firebug's error count
        // TRACE        Displays a log message with an expandable stack trace
        // EXCEPTION    Displays an error long message with an expandable stack trace
        // TABLE        Displays a log message with an expandable table
        $loggerResource = $this->getPluginResource('log');
        $this->_logger = $loggerResource->getLog();

        if ('development' === $this->_application->getEnvironment()) {
            $writer = new Zend_Log_Writer_Firebug();
            $writer->setPriorityStyle(8, 'TABLE');
            $this->_logger->addWriter($writer);
            $this->_logger->addPriority('TABLE', 8);

        //  //$writer->setPriorityStyle(Zend_Log::DEBUG, 'TRACE');
        //  $writer->addFilter(Zend_Log::DEBUG);
        //  //$logger->setDefaultPriorityStyle('TRACE');
        //  //$logger->setEventItem('pid', getmypid());
        //  $this->logger->addWriter($writer);
        //
        //  //$writer = new Zend_Log_Writer_Stream('php://output');
        //
        }

        $this->_logger->log(__METHOD__ . ' Putting logger into Registry', Zend_Log::DEBUG);
        Zend_Registry::set('logger', $this->_logger);
    }

    protected function _initDbProfiler()
    {
        $this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

        if ($this->_application->getEnvironment() == 'development') {
            $this->bootstrap('db');
            $profiler = new Zend_Db_Profiler_Firebug(
                'All DB Queries'
            );
            $profiler->setEnabled(true);
            $this->getPluginResource('db')->getDbAdapter()->setProfiler($profiler);
        }

        $this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
    }

    //protected function _initNavigation()
    //{
    //
    //  $this->bootstrap('layout');
    //  $layout = $this->getResource('layout');
    //  $view = $layout->getView();
    //  $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'nav');
    //
    //  $navigation = new Zend_Navigation($config);
    //
    //  $view->navigation($navigation);
    //  $this->_logger->log(__METHOD__. ' End', Zend_Log::INFO);
    //}


    protected function _initLocale()
    {
        Zend_Registry::set(
            'Zend_Locale',
            new Zend_Locale('en_GB')
        );
    }

    protected function _initRouting()
    {
        $this->bootstrap('logging');

        $front  = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();

        // image cache router
        $routeImageCache = new Zend_Controller_Router_Route_Regex (
            'photos/(.+)/(.+)/(.+)_(.+)x(.+)\.(.+)',
            array (
              'module'      => 'frontend',
              'controller'  => 'image-cache',
              'action'      => 'generate'
            ),
            array (
              1 => 'topLevel',
              2 => 'idProperty',
              3 => 'idPhoto',
              4 => 'width',
              5 => 'height',
              6 => 'ext'
            )
        );

        // RESTful API Interface
        $restPropertiesRatesA = new Zend_Controller_Router_Route(
            '/api/properties/:idProperty/rates',
            array (
                'module'     => 'api',
                'controller' => 'property-rates',
                'action'     => 'test'
            )
        );

        $restPropertiesRatesB = new Zend_Controller_Router_Route(
            '/api/properties/:idProperty/rates/:idRate',
            array (
                'module'     => 'api',
                'controller' => 'property-rates',
                'action'     => 'index'
            )
        );

        $restPropertyCalendar = new Zend_Controller_Router_Route(
            '/api/property/:idProperty/calendar/:idCalendar',
            array (
                'module'     => 'api',
                'controller' => 'property-calendar',
                'action'     => 'index'
            )
        );

        $restPropertyPhotos = new Zend_Controller_Router_Route(
            '/api/property/:idProperty/photos',
            array (
                'module'     => 'api',
                'controller' => 'property-photo',
                'action'     => 'index'
            )
        );

        $routeRegion = new Zend_Controller_Router_Route (
            '/in/:country/:region',
            array (
                'module'     => 'frontend',
                'controller' => 'level',
                'action'     => 'region'
            )
        );

//      Zend_Controller_Front::getInstance()->getRouter()->addRoute('frontend',$route);

//      $this->logger->log("Bootstrap_Bootstrap _initRouting() setting up route for in/country", Zend_Log::DEBUG);
        //$routeCountry = new Zend_Controller_Router_Route('/in/:country',
        //                                            array('module'        => 'frontend',
        //                                                  'controller'    => 'level',
        //                                                  'action'        => 'country')
        //);

        //$routeLocation = new Zend_Controller_Router_Route_Regex('in/(\w+)(?:/(\w+))',
        //                                                      array ('module'     => 'frontend',
        //                                                             'controller' => 'level',
        //                                                             'action'     => 'test'));

        $routeRestApi = new Zend_Rest_Route($front, array (), array ('api'));

        $locationRouter = new Vfr_Controller_Router_Route_Location(
            '([a-z0-9-/]*)'
        );

        //  /^(.(?!foo).(?!wobble))*$/
        // /^((?!admin).(?!login).)*$/
        $propertyRouter = new Vfr_Controller_Router_Route_Property(
            '([a-z0-9-/]*)'
        );

        $urlRedirectRouter = new Vfr_Controller_Router_Route_UrlRedirect(
            '([a-z0-9-/]*)'
        );

        // these trigger in reverse order i.e. property check first, then location
        $router->addroute('location', $locationRouter)
               ->addroute('property', $propertyRouter)
               ->addroute('urlRedirect', $urlRedirectRouter)
               ->addroute('imageCache', $routeImageCache)
               ->addRoute('restPropertyCalendar', $restPropertyCalendar)
               ->addRoute('ratePropertiesRatesB', $restPropertiesRatesB)
               ->addRoute('restPropertiesRatesA', $restPropertiesRatesA)
               ->addRoute('restPropertyPhotos', $restPropertyPhotos);
               //->addRoute('rest', $routeRestApi);

        $this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
    }


    protected function _initPlugins()
    {
        $this->bootstrap('logging');
        $frontController = Zend_Controller_Front::getInstance();

        $layoutModulePlugin = new Common_Plugin_Layout();

        $layoutModulePlugin->registerModuleLayout(
            'frontend',
            APPLICATION_PATH . '/modules/frontend/layouts/scripts'
        );

        $layoutModulePlugin->registerModuleLayout(
            'controlpanel',
            APPLICATION_PATH . '/modules/controlpanel/layouts/scripts'
        );

        $layoutModulePlugin->registerModuleLayout(
            'admin',
            APPLICATION_PATH . '/modules/admin/layouts/scripts'
        );

        //$acl = new Frontend_Model_AdvertiserAcl();

        // codemagician: all you need to do in bootstrap is: $layoutModulePlugin= new Zend_View_Plugin_Layout();
        // $layoutModulePlugin->registerModuleLayout("mymodule", APPLICATION_PATH . "/modules/mymodule/layouts/scripts", "layout");

        //$frontController->registerPlugin(new Frontend_Plugin_AdvertiserAccessCheck());
        $frontController->registerPlugin($layoutModulePlugin);

        // switch on HTTP Digest Authentication for the admin module
        // only if we're live
        if (APPLICATION_ENV != 'beta')
            $frontController->registerPlugin(
                new Admin_Plugin_Auth()
            );

        $frontController->registerPlugin(
            new Api_Plugin_RestAuth()
        );
    }

    protected function _initActionHelpers()
    {
        $this->bootstrap('logging');
        $this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

        //Zend_Controller_Action_HelperBroker::addPrefix('Frontend_Helper');
        Zend_Controller_Action_HelperBroker::addPath(
            APPLICATION_PATH . '/modules/frontend/helpers', 'Frontend_Helper'
        );

        Zend_Controller_Action_HelperBroker::addPath(
            APPLICATION_PATH . '/modules/controlpanel/helpers', 'Controlpanel_Helper'
        );

        Zend_Controller_Action_HelperBroker::addPath(
            APPLICATION_PATH . '/modules/api/helpers', 'Api_Helper'
        );

        //Zend_Controller_Action_HelperBroker::addPath(
        //    APPLICATION_PATH . '/modules/admin/helpers', 'Admin_Helper');
        //Zend_Controller_Action_HelperBroker::addPrefix('Admin_Helper');

        //Zend_Controller_Action_HelperBroker::addHelper(
        //    new Vfr_Controller_Helper_Acl()
        //);
        //die();

        //Zend_Controller_Action_HelperBroker::addHelper(new Admin_Helper_Auth());

        $this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
    }


    protected function _initViewHelpers()
    {
        $this->bootstrap('logging');

        //$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

        $this->_logger->log("Bootstrap_Bootstrap _initViewHelpers() starting the MVC system", Zend_Log::DEBUG);
        Zend_Layout::startMvc();
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        ZendX_JQuery::enableView($view);

        //$view->doctype('HTML5');
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
        $view->headMeta()->appendHttpEquiv('Content-Language', 'en-GB');
        $view->headMeta()->appendHttpEquiv('author', 'Andy Fusniak');

        //$view->addHelperPath('Vfr/View/Helper', 'Vfr_View_Helper');
        //var_dump($view->min(14));
        //$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
        return $view;
    }
}
