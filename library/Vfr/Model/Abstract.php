<?php
/**
 * Base model class that all our models will inherit from.
 */
abstract class Vfr_Model_Abstract implements Vfr_Model_Interface
{
	protected $_logger = null;
	protected $_profiler = null;

	public function __construct()
	{
		if (null === $this->_logger) {
			$this->_logger = Zend_Registry::get('logger');
		}

		$this->init();
	}

	public function init()
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

		if (null === $this->_profiler) {
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$this->_profiler = $dbAdapter->getProfiler();
			$this->_logger->log(__METHOD__ . ' Profiler stored in abstract class', Zend_Log::DEBUG);
		}

		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
	}

	protected $_resources = array();

	public function getResource($name)
	{
		// check if the 'Model Resource' has already been loaded
		if (!isset($this->_resources[$name])) {
			$class = join('_', array(
				$this->_getNamespace(),
				'Resource',
				//$this->_getInflected($name)
				$name
			));

			$this->_resources[$name] = new $class();
		}

		return $this->_resources[$name];
	}

	private function _getNamespace()
	{
		$ns = explode('_', get_class($this));
		return $ns[0];
	}

	private function _getInflected($name)
	{
		$inflector = new Zend_Filter_Inflector(':class');
	
		$inflector->setRules(array(
			':class'  => array('Word_CamelCaseToUnderscore')
		));

		return ucfirst($inflector->filter(array('class' => $name)));
	}
}