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
	
	/*
	public static function generateChecksum($data, $generateMatrix=false)
	{
		// filter out only what we need
		$params = array (
			'locationUrl'   	=> (isset($data['locationUrl']) ? $data['locationUrl'] : ''),	// 1
			'metaData'			=> (isset($data['metaData']) ? $data['metaData'] : ''),			// 2
			'seoData'			=> (isset($data['seoData']) ? $data['seoData'] : ''),			// 3
			'website'			=> (isset($data['website']) ? $data['website'] : ''),			// 4
			'headline1'			=> (isset($data['headline1']) ? $data['headline1'] : ''),		// 5
			'headline2'			=> (isset($data['headline2']) ? $data['headline2'] : ''),		// 6
			'summary'			=> (isset($data['summary']) ? $data['summary'] : ''),			// 7
			'description'		=> (isset($data['description']) ? $data['description'] : ''),	// 8
			'bedroomDesc'		=> (isset($data['bedroomDesc']) ? $data['bedroomDesc'] : ''),	// 9
			'bathroomDesc'		=> (isset($data['bathroomDesc']) ? $data['bathroomDesc'] : ''),	// 10
			'kitchenDesc'		=> (isset($data['kitchenDesc']) ? $data['kitchenDesc'] : ''),	// 11
			'utilityDesc'		=> (isset($data['utilityDesc']) ? $data['utilityDesc'] : ''),	// 12
			'livingDesc'		=> (isset($data['livingDesc']) ? $data['livingDesc'] : ''),		// 13
			'otherDesc'			=> (isset($data['otherDesc']) ? $data['otherDesc'] : ''),		// 14
			'serviceDesc'		=> (isset($data['serviceDesc']) ? $data['serviceDesc'] : ''),	// 15
			'notesDesc'			=> (isset($data['notesDesc']) ? $data['notesDesc'] : ''),		// 16
			'accessDesc'		=> (isset($data['accessDesc']) ? $data['accessDesc'] : ''),		// 17
			'outsideDesc'		=> (isset($data['outsideDesc']) ? $data['outsideDesc'] : ''),	// 18
			'golfDesc'			=> (isset($data['golfDesc']) ? $data['golfDesc'] : ''),			// 19
			'skiingDesc'		=> (isset($data['skiingDesc']) ? $data['skiingDesc'] : ''),		// 20
			'specialDesc'		=> (isset($data['specialDesc']) ? $data['specialDesc'] : ''),	// 21
			'beachDesc'			=> (isset($data['beachDesc']) ? $data['beachDesc'] : ''),		// 22
			'travelDesc'		=> (isset($data['travelDesc']) ? $data['travelDesc'] : ''),		// 23
			'bookingDesc'		=> (isset($data['bookingDesc']) ? $data['bookingDesc'] : ''),	// 24
			'testimonialsDesc'	=> (isset($data['testimonialsDesc']) ? $data['testimonialsDesc'] : ''), // 25
			'changeoverDesc'	=> (isset($data['changeoverDesc']) ? $data['changeoverDesc'] : ''),		// 26
			'contactDesc'		=> (isset($data['contactDesc']) ? $data['contactDesc'] : ''),			// 27
			'country'			=> (isset($data['country']) ? $data['country'] : ''),					// 28
			'region'			=> (isset($data['region']) ? $data['region'] : ''),						// 29
			'location'			=> (isset($data['location']) ? $data['location'] : ''),					// 30
		);
	
		if ($generateMatrix) {
			foreach ($params as $name=>$value) {
				$params[$name] = Vfr_Checksum::cs($value);
			}
			
			return $params;
		}
	
		$stream = '';
		foreach ($params as $name=>$value) {
			$stream .= $value;
		}
		
		return sha1($stream);
	}
	
	*/
	
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

/*
    public function checksumTotal($idProperty, $version=Common_Resource_PropertyContent::VERSION_MAIN, $lang='EN')
	{
		$query->select(new Zend_Db_Expr('sha1(group_concat(cs)) AS cs'))
			  ->where('version=?', $version)
			  ->where('iso2char=?', $lang)
			  ->where('idProperty=?', $idProperty)
			  ->order('idPropertyContentField ASC');
	
		try {
			$row = $this->fetchRow($query);
			
			return $row->cs;
		} catch (Exception $e) {
			throw $e;
		}
		
		//$query = $this->select('cs')
		//$query = $this->select()->from($this->_name, 'cs')
		//			  ->where('idProperty = ?', $idProperty)
		//			  ->where('version = ?', $version)
		//			  ->where('iso2char = ?', $lang)
		//			  ->order('idPropertyContentField');

		//try {
		//	$propertyContentRowset = $this->fetchAll($query);

		//	// concat the checksums together.
		//	// null strings should be treated as empty strings
		//	$checksumSum = '';
		//	foreach ($propertyContentRowset as $propertyContentRow) {
		//		if ($propertyContentRow->cs == null)
		//			$content = '';
		//		else
		//			$content = $propertyContentRow->cs;
		//			
		//		$checksumSum .= $content;
		//	}

		//	# get the hash of the concatted hashes
		//	return Vfr_Checksum::cs($checksumSum);
		//} catch (Exception $e) {
		//	throw $e;
		//}
	}
*/

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
		
		/*
		 * This query gets a list of fields that need to be changed
		 *
		 * SELECT DISTINCT t1.idPropertyContent, t2.content, t2.cs, t1.idProperty
		 * FROM PropertiesContent AS t1 INNER JOIN PropertiesContent AS t2
		 * WHERE t1.version = 1 AND t2.version = 2
		 * AND t1.idProperty = 10500
		 * AND t1.iso2char = 'EN'
		 * AND t1.idProperty = t2.idProperty
		 * AND t1.iso2char = t2.iso2char
		 * AND t1.idPropertyContentField = t2.idPropertyContentField
		 * AND t1.cs != t2.cs
		 */
		//$query = $this->select()
		//		      ->from(
		//					array ('t1' => 'PropertiesContent'),
		//					array(new Zend_Db_Expr('DISTINCT t1.idPropertyContent'), 't1.idProperty', 't2.content', 't2.cs')
		//				)->joinInner(
		//					array('t2' => 'PropertiesContent'),
		//					'
		//					t1.idPropertyContentField=t2.idPropertyContentField
		//					AND t1.idProperty=t2.idProperty
		//					AND t1.cs!=t2.cs
		//					AND t1.iso2char=t2.iso2char
		//					',
		//					array('content', 'cs')
		//				)->where('t1.version=1 AND t2.version=1')
		//			     ->where('t1.idProperty=?', 10500)
		//				 ->where('t1.iso2char=?', 'EN');
	
		//try {
		//	$propertyContentRowset = $this->fetchAll($query);
		//	
		//	die();
		//	foreach ($propertyContentRowset as $propertyContentRow) {
		//		$params = array (
		//			'content' => $propertyContentRow->content,
		//			'cs' => $propertyContentRow->cs,
		//			'updated' => new Zend_Db_Expr('NOW()')
		//		);
				
		//		$whereClause = $this->getAdapter()->quoteInto('idPropertyContent=?', $propertyContentRow->idPropertyContent);
		//		try {
        //   $query = $this->update($params, $whereClause);
        //} catch (Exception $e) {
        //   throw $e;
        //}
		//	}
			
			
		//	var_dump($propertyContentRowset);
		//	die();
		//} catch (Exception $e) {
		//	throw $e;
		//}
	
		//$query = $this->select()
		//		      ->from($this->_name, array('t1.idPropertyContent', 't2.content', 't2.cs'))
		//			  ->where('t1.version=?', (int) 1)
		//			  ->where('t2.version=?', (int) 2)
		//			  ->where('t1.idProperty=?', (int) $idProperty)
		//			  ->where('t1.iso2char=?', 'EN')
		//			  ->where('t1.');
			
		$sql = "
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
					t1.version=1 AND t1.idProperty=10519 AND t1.iso2char='EN'
				)
				AS tm
				ON
					tm.idPropertyContent=c.idPropertyContent
				SET
					c.content=tm.content, c.cs=tm.cs, c.updated=now()
		";
		
		die(); 
		
		$mPropertyContentRowset = $this->getPropertyContentByPropertyId($idProperty, self::VERSION_MAIN);
		$uPropertyContentRowset = $this->getPropertyContentByPropertyId($idProperty, self::VERSION_UPDATE);
		
		
		$propertyContentRowset = $this->getPropertyContentByPropertyId($idProperty, self::VERSION_UPDATE);
		
		
		$stream = '';
		foreach ($propertyContentRowset as $row) {
			$this->updatePropertyContent($idProperty, self::VERSION_MAIN, 'EN', $row->idPropertyContentField, $row->content);
			$content = $row->content;
			if ($content == null)
				$content = '';
				
			$stream .= Vfr_Checksum::cs($content);
		}
		
		return sha1($stream);
	}
}
	
