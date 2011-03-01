<?php
class Vfr_Application_Service_Cron
{
    protected $_loader;
    protected $_actions = array();
    protected $_actionsArgs = array();
    protected $_errors = array();

    public function __construct(array $pluginPaths)
    {
        $this->_loader = new Zend_Loader_PluginLoader($pluginPaths);
    }

    /**
     * Get loader
     *
     * @return Zend_Loader_PluginLoader
     */
    public function getLoader()
    {
        return $this->_loader;
    }

    /**
     * Runs all registered cron actions.
     *
     * @return string any errors that may have occurred
     */
    public function run()
    {
        foreach ($this->_actions as $key => $action) {
			var_dump($this->getLoader());
            $class = $this->getLoader()->load($action);
    		var_dump($class);
			if (null !== $this->_actionsArgs[$key]) {
                $action = new $class($this->_actionsArgs[$key]);
            } else {
                $action = new $class;
            }

            if (!($action instanceof Frontend_Plugin_Cron_CronInterface)) {
                throw new Service_Exception('One of the specified actions is not the right kind of class.');
            }

            try {
                $action->run();
            } catch (Plugin_Cron_Exception $e) {
                $this->addError($e->getMessage());
            } catch (Exception $e) {
                if (APPLICATION_ENV == 'development') {
                    $this->addError('[DEV]: ' . $e->getMessage());
                } else {
                    $this->addError('An undefined error occurred.');
                }
            }
        }

        $errors = $this->getErrors();
        if (count($errors) > 0) {
            $output = '<html><head><title>Cron errors</title></head><body>';
            $output .= '<h1>Cron errors</h1>';
            $output .= '<ul>';
            foreach ($errors as $error) {
                $output .= '<li>' . $error . '</li>';
            }
            $output .= '</ul>';
            $output .= '</body></html>';
        } else {
            $output = null;
        }

        return $output;
    }

    public function addAction($action, $args = null)
    {
        $key = count($this->_actions) + 1;
        $this->_actions[$key] = $action;
        $this->_actionsArgs[$key] = $args;
        return $this;
    }

    public function addError($message)
    {
        $this->_errors[] = $message;
        return $this;
    }

    public function getErrors()
    {
        return $this->_errors;
    }
}
