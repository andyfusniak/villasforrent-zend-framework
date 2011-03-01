<?php
class Common_Resource_Property extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_Property_Interface
{
	protected $_name = 'Properties';
	protected $_primary = 'idProperty';
	protected $_rowClass = 'Common_Resource_Property_Row';
	protected $_rowsetClass = 'Common_Resource_Property_Rowset';
	protected $_referenceMap = array (
		'PropertyType' => array (
			'columns' => array('idPropertyType'),
			'refTableClass' => 'Common_Resource_PropertyType'
		),

		'Advertiser' => array (
			'columns' => array('idAdvertise'),
			'refTableClass' => 'Common_Resource_Advertiser'
		),

		'Country' => array (
			'columns' => array('idCountry'),
			'refTableClass' => 'Common_Resource_Country'
		),

		'Region' => array (
			'columns' => array('idRegion'),
			'refTableClass' => 'Common_Resource_Region'
		),

		'Destination' => array (
			'columns' => array('idDestination'),
			'refTableClass' => 'Common_Resource_Destination'
		)
	);
	
	private $_approved = null;
	private $_visible = null;
	private $_count = null;
	private $_idCountry = null;
	private $_idRegion = null;
	private $_idDestination = null;

	const DEFAULT_PROPERTY_ID = 10000;

	private function _initDefaults()
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

		// setup the default search settings
		$this->_approved = false;
		$this->_visible  = false;
		$this->_count = false;
		$this->_idCountry 		= null;
		$this->_idRegion		= null;
		$this->_idDestination 	= null;
		
		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
	}

	private function _overrideDefaults($options)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		
		// override the default settings with the search options passed
		if (isset($options['approved'])) {
			$this->_approved = $options['approved'];
		}

		if (isset($options['visible'])) {
			$this->_visible = $options['visible'];
		}

		if (isset($options['count'])) {
			// use Zend_Filter_Boolean here
			$this->_count = $options['count'];
		}
		
		if (isset($options['idCountry'])) {
			$this->_idCountry = $options['idCountry'];
		}
		
		if (isset($options['idRegion'])) {
			$this->_idRegion = $options['idRegion'];
		}
		
		if (isset($options['idDestination'])) {
			$this->_idDestination = $options['idDestination'];
		}

		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
	}

	public function doSearch($options)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

		$this->_initDefaults();
		
		if (null !== $options) {
			$this->_overrideDefaults($options);
		}
		
		$properties = null;
		
		$query = $this->select();
		$query->from(array('P' => 'Properties'));
		
		if (true === $this->_approved) {
			$query->where('P.approved = ?', (int) $this->_approved);
		}
		
		if (true === $this->_visible) {
			$query->where('P.visible = ?', (int) $this->_visible);
		}
		
		if (null !== $this->_idCountry) {
			$query->where('P.idCountry = ?', (int) $this->_idCountry);
		}
		
		if (null !== $this->_idRegion) {
			$query->where('P.idRegion = ?', (int) $this->_idRegion);
		}
		
		if (null !== $this->_idDestination) {
			$query->where('P.idDestination = ?', (int) $this->_idDestination);
		}

		if (true === $this->_count) {
			$query->reset(Zend_Db_Select::COLUMNS);
			$query->reset(Zend_Db_Select::FROM);
			$query->from(
				array('P' => 'Properties'),
				new Zend_Db_Expr('COUNT(*)')
			);
			$this->_logger->log(__METHOD__ . ' SQL Query=' . $query->__toString(), Zend_Log::INFO);
			$result = $this->_db->fetchOne($query);
			return $result;
		}

		$this->_logger->log(__METHOD__ . ' SQL Query=' . $query->__toString(), Zend_Log::INFO);
		
		$properties = $this->fetchAll($query);
		
		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
		return $properties;
	}

	public function getPropertyById($idProperty)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

		$query = $this->select();
		$query->from(array('P' => 'Properties'), array('*'));
		
		//$query->join(array('C' => 'PropertiesContent'), 'P.idProperty = C.idProperty', array());
		$query->where('P.idProperty = ?', $idProperty);
		
		try {
			$result = $this->fetchRow($query);
		} catch (Exception $e) {
			echo $query;
			exit;
			if ($this->_profiler->getEnabled()) {
				$profilerQuery = $this->_profiler->getLastQueryProfile();
				Zend_Debug::dump($profilerQuery->getQuery(), 'Profiler: ');
				Zend_Debug::dump($profilerQuery->getQueryParams(), 'Profiler: ');
				exit;
			}
			throw $e;
		}
		
		
		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
		return $result;	
	}

	public function addProperty()
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		
		$data = array(
			'idProperty' => new Zend_Db_Expr('NULL'),
			'added'   =>  new Zend_Db_Expr('NOW()'),
			'updated' =>  new Zend_Db_Expr('NOW()') 
		);

		try {
			$this->insert($data);
		} catch (Exception $e) {
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$profiler = $dbAdapter->getProfiler();
			$lastDbProfilerQuery = $profiler->getLastQueryProfile();

			var_dump($lastDbProfilerQuery->getQuery());

			throw $e;
		}

		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
	}
    
    public function createProperty($params)
    {
        $this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
        
        
        
        $this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
    }
}

