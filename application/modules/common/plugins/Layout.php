<?php
class Common_Plugin_Layout extends Zend_Controller_Plugin_Abstract
{
    private $_logger;

    /**
     * Array of layout paths associating modules with layouts
     */
    protected $_moduleLayouts;

    public function __construct()
    {
        $this->_logger = Zend_Registry::get('logger');
    }

    /**
     * Registers a module layout.
     * This layout will be rendered when the specified module is called.
     * If there is no layout registered for the current module, the default layout as specified
     * in Zend_Layout will be rendered
     *
     * @param String $module        The name of the module
     * @param String $layoutPath    The path to the layout
     * @param String $layout        The name of the layout to render
     */
    public function registerModuleLayout($module, $layoutPath, $layout=null)
    {
        $this->_moduleLayouts[$module] = array('layoutPath' => $layoutPath,
                                               'layout'     => $layout);
    }

    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        //$this->_logger->log(__METHOD__ . ' Start', Zend_Log::DEBUG);
        //$this->_logger->log(__METHOD__ . ' End', Zend_Log::DEBUG);
    }

    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        //$this->_logger->log(__METHOD__ . ' Start', Zend_Log::DEBUG);
        //$this->_logger->log(__METHOD__ . ' End', Zend_Log::DEBUG);
    }

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        //$this->_logger->log(__METHOD__ . ' Start', Zend_Log::DEBUG);
        //$this->_logger->log(__METHOD__ . ' End', Zend_Log::DEBUG);
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        //$this->_logger->log(__METHOD__ . ' Start', Zend_Log::DEBUG);
        $moduleName = $request->getModuleName();

        $this->_logger->log(__METHOD__ . ' getModuleName()=' . $moduleName, Zend_Log::DEBUG);
        if (isset($this->_moduleLayouts[$moduleName])) {
            $config = $this->_moduleLayouts[$moduleName];
            $layout = Zend_Layout::getMvcInstance();
            if ($layout->getMvcEnabled()) {
                $this->_logger->log('Setting layout path to ' . $config['layoutPath'], Zend_Log::DEBUG);
                $layout->setLayoutPath($config['layoutPath']);

                if (null !== $config['layout']){
                    $layout->setLayout($config['layout']);
                }
            }
        }



        switch($moduleName) {
            case 'admin':
                $view = $layout->getView();

                //$this->_logger->log(__METHOD__ . ' Setting view helpers for admin module', Zend_Log::DEBUG);
                //$view->headLink()->prependStylesheet('/js/yui2/reset-fonts-grids/reset-fonts-grids.css');
                $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/admin-navigation.xml', 'nav');

                $adminNav = new Zend_Navigation($config);
                $view->navigation($adminNav);
                //$view->navigation()->menu()->setPartial(array('nav1.phtml','default'));
            break;

            case 'controlpanel':
                $front = Zend_Controller_Front::getInstance();
                $view = $layout->getView();

                //$config = new Zend_Config_Xml(
                //    APPLICATION_PATH . '/configs/advertisercp-navigation.xml',
                //    'nav'
                //);

                // Controller based ACL for advertiser controlpanel
                $advertiserControlpanelAcl = new Controlpanel_Model_Acl_Controlpanel();

                // setup the navigation bar for the advertiser controlpanel
                //$advertiserCpNav = new Zend_Navigation($config);
                //$view->navigation($advertiserCpNav)
                //     ->setAcl($advertiserControlpanelAcl);

                $view->headMeta()->appendHttpEquiv('robots', 'NOINDEX, NOFOLLOW');
                $view->headMeta()->appendHttpEquiv('description', 'Control Panel');
                $view->headTitle(
                    'Control Panel'
                );
            break;
        }


        //$this->_logger->log(__METHOD__ . ' End', Zend_Log::DEBUG);
    }

    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        //$this->_logger->log(__METHOD__ . ' Start', Zend_Log::DEBUG);
    }

    public function dispatchLoopShutdown()
    {
        //$this->_logger->log(__METHOD__ . ' Start', Zend_Log::DEBUG);
        //$this->_logger->log(__METHOD__ . ' End', Zend_Log::DEBUG);
    }
}
