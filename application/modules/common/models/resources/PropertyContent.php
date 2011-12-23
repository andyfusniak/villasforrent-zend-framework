<?php
class Common_Resource_PropertyContent
    extends Vfr_Model_Resource_Db_Table_Abstract
    implements Common_Resource_PropertyContent_Interface
{
	protected $_name = 'PropertiesContent';
	protected $_primary = 'idPropertyContent';
	protected $_rowClass = 'Common_Resource_PropertyContent_Row';
	protected $_rowsetClass = 'Common_Resource_PropertyContent_Rowset';
	protected $_referenceMap = array (
		'Property' => array (
			'columns' => array('idProperty'),
			'refTableClass' => 'Common_Resource_Property'
		)
	);
	
	const VERSION_MAIN 		= 1;
	const VERSION_UPDATE 	= 2;
	
	const VERSION_BOTH	= 0;
	
	// Content IDs
	const FIELD_TOTAL_ROWS	= 30;
	
	const FIELD_LOCATION_URL 		= 1;
	const FIELD_META_DATA 			= 2;
	const FIELD_SEO_DATA 			= 3;
	const FIELD_WEBSITE 			= 4;
	const FIELD_HEADLINE_1 			= 5;
	const FIELD_HEADLINE_2 			= 6;
	const FIELD_SUMMARY 			= 7;
	const FIELD_DESCRIPTION 		= 8;
	const FIELD_BEDROOM_DESC		= 9;
	const FIELD_BATHROOM_DESC 		= 10;
	
	const FIELD_KITCHEN_DESC 		= 11;
	const FIELD_UTILITY_DESC 		= 12;
	const FIELD_LIVING_DESC 		= 13;
	const FIELD_OTHER_DESC 			= 14;
	const FIELD_SERVICE_DESC 		= 15;
	const FIELD_NOTES_DESC 			= 16;
	const FIELD_ACCESS_DESC 		= 17;
	const FIELD_OUTSIDE_DESC 		= 18;
	const FIELD_GOLF_DESC 			= 19;
	const FIELD_SKIING_DESC 		= 20;
	
	const FIELD_SPECIAL_DESC 		= 21;
	const FIELD_BEACH_DESC 			= 22;
	const FIELD_TRAVEL_DESC 		= 23;
	const FIELD_BOOKING_DESC 		= 24;
	const FIELD_TESTIMONIALS_DESC 	= 25;
	const FIELD_CHANGEOVER_DESC  	= 26;
	const FIELD_CONTACT_DESC 		= 27;
	const FIELD_COUNTRY 			= 28;
	const FIELD_REGION 				= 29;
	const FIELD_LOCATION 			= 30;
	
	//
	// CREATE
	//
	
	public function createPropertyContent($idProperty, $version, $lang, $idPropertyContentField, $content)
	{
		$nowExpr = new Zend_Db_Expr('NOW()');
		$data = array (
			'idPropertyContent' 		=> new Zend_Db_Expr('NULL'),
			'idProperty'				=> $idProperty,
			'version'					=> $version,
			'idPropertyContentField'	=> $idPropertyContentField,
			'iso2char'					=> $lang,
			'content'					=> $content,
			'added'						=> $nowExpr,
			'updated'					=> $nowExpr
		);
		
		try {
			$this->insert($data);
		} catch (Exception $e) {
			//$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			//$profiler = $dbAdapter->getProfiler();
			//$lastDbProfilerQuery = $profiler->getLastQueryProfile();

			//var_dump($lastDbProfilerQuery->getQuery());
			throw $e;
		}
	}
	
	//
	// READ
	//
    /**
     *
     * @param int $idProperty
     * @return Common_Resource_PropertyContent_Rowset
     */
	public function getPropertyContentByPropertyId($idProperty, $version=self::VERSION_MAIN,
        $lang='EN', $idPropertyContentFieldList = null)
	{
		$query = $this->select()
					  ->where('idProperty = ?', $idProperty)
					  ->where('version = ?', $version)
					  ->where('iso2char = ?', $lang);
		
		//die($query);
		if (($idPropertyContentFieldList) && (is_array($idPropertyContentFieldList))) {
			$query->where('idPropertyContentField IN (?)', $idPropertyContentFieldList);
		}
		
		$query->order('idPropertyContentField ASC');
		
		try {
			return $this->fetchAll($query);
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	public function getPropertyContentByPropertyList($propertyRowset, $version, $lang, $idPropertyContentFieldList)
	{
		$propertyList = array();
		foreach ($propertyRowset as $propertyRow) {
			array_push($propertyList, $propertyRow->idProperty);
		}
		
		$query = $this->select()
					  ->where('idProperty IN (?)', $propertyList)
					  ->where('version = ?', $version)
					  ->where('iso2char = ?', $lang);
		//die($query);
		if (($idPropertyContentFieldList) && (is_array($idPropertyContentFieldList))) {
			$query->where('idPropertyContentField IN (?)', $idPropertyContentFieldList);
		}
		
		try {
			$propertyContentRowset = $this->fetchAll($query);
			
			return $propertyContentRowset;
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function updatingPropertyContentDataset($idProperty, $version, $params)
	{
		$query = $this->select(array('idProperty', 'idPropertyContentField', 'cs'))
		              ->where('idProperty = ?', $idProperty)
			          ->where('version = ?', $version)
			          ->where('iso2char = ?', 'EN')
					  ->where('idPropertyContentField NOT IN (?)',
							  array(
								Common_Resource_PropertyContent::FIELD_LOCATION_URL,
								Common_Resource_PropertyContent::FIELD_META_DATA,
								Common_Resource_PropertyContent::FIELD_SEO_DATA,
								Common_Resource_PropertyContent::FIELD_COUNTRY,
								Common_Resource_PropertyContent::FIELD_REGION,
								Common_Resource_PropertyContent::FIELD_LOCATION
					  )
			     );
		
		try {
			$propertyContentRowset = $this->fetchAll($query);

			$mappings = Common_Resource_PropertyContentField::$propertiesContentFieldsMap;			
			$resultList = array ();
			foreach ($propertyContentRowset as $item) {
				$fieldName = $mappings[$item->idPropertyContentField];
			
				// check if the field has changed	
				if (sha1($params[$fieldName]) != $item->cs) {
					$this->updatePropertyContent($idProperty, $version, 'EN', $item->idPropertyContentField, $params[$fieldName]);
				}
			}
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	//
	// UPDATE
	//
	
	public function updatePropertyContent($idProperty, $version, $lang, $idPropertyContentField, $content)
	{
		$params = array (
			'content'	=> $content,
			'updated'	=> new Zend_Db_Expr('NOW()')
		);
		
		$adapter = $this->getAdapter();
		$where = $adapter->quoteInto('idProperty=?', $idProperty)
			   . $adapter->quoteInto(' AND version=?', $version)
			   . $adapter->quoteInto(' AND iso2char=?', $lang)
			   . $adapter->quoteInto(' AND idPropertyContentField=?', $idPropertyContentField);
		try {
			$query = $this->update($params, $where);
		} catch (Exception $e) {
			throw $e;
		}
	}

    public function repairPropertyBatchChecksums() {
        $expr = new Zend_Db_Expr('sha1(content)');
        $params = array (
            'cs' => $expr
        );

        $adapter = $this->getAdapter();
		$whereClause = $adapter->quoteInto('(?)', (int) 1);
        try {
			$query = $this->update($params, $whereClause);
		} catch (Exception $e) {
			throw $e;
		}
    }
	
	public function copyUpdateToMaster($idProperty)
	{
		$adapter = $this->getAdapter();
		
		$query = $adapter->quoteInto("
			UPDATE PropertiesContent AS c
				JOIN
				(
				SELECT DISTINCT
					t1.idPropertyContent, t1.idProperty, t2.content, t2.cs
				FROM
					PropertiesContent AS t1
				INNER JOIN PropertiesContent AS t2 ON
					t1.idPropertyContentField=t2.idPropertyContentField
					AND t1.idProperty=t2.idProperty
					AND t1.cs!=t2.cs
					AND t1.iso2char=t2.iso2char
				WHERE
					t1.version=1 AND t1.iso2char='EN' AND t1.idProperty=?
				)
				AS tm
				ON
					tm.idPropertyContent=c.idPropertyContent
				SET
					c.content=tm.content, c.cs=tm.cs, c.updated=now()
		", $idProperty);
		
		try {
			$this->_db->query($query);
		} catch (Exception $e) {
			throw $e;
		}
	}
}
	
