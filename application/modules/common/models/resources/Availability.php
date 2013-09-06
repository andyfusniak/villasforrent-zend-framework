<?php
class Common_Resource_Availability extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_Availability_Interface
{
    const BOOKED = 1;

    protected $_name = 'Availability';
    protected $_primary = 'idAvailability';
    protected $_rowClass = 'Common_Resource_Availability_Row';
    protected $_rowsetClass = 'Common_Resource_Availability_Rowset';
    protected $_referenceMap = array (
        'Calendar' => array (
            'columns' => array('idCalendar'),
            'refTableClass' => 'Common_Resource_Calendar'
        )
    );

    //
    // CREATE
    //

    public function addNewBooking($idCalendar, $params)
    {
        // getBookingCollisions($idCalendar, $startDate, $endDate)

        $pattern = '/' . Vfr_Form_Element_AvailabilityRangePicker::DELIMITER . '/';
        list ($startDate, $endDate) = preg_split($pattern, $params['availability']);

        var_dump($startDate, $endDate);
        //die();

        $nullExpr = new Zend_Db_Expr('NULL');
        $nowExpr  = new Zend_Db_Expr('NOW()');
        $data = array(
            'idAvailability'    => $nullExpr,
            'idCalendar'        => $idCalendar,
            'startDate'         => $startDate,
            'endDate'           => $endDate,
            'st'                => self::BOOKED
        );

        try {
            $this->insert($data);
            $rowId = $this->_db->lastInsertId();

            return $rowId;
        } catch (Exception $e) {
            throw $e;
        }
    }

    //
    // READ
    //

    public function getAvailabilityByCalendarId($idCalendar)
    {
        $availbility = null;

        $query = $this->select()
                      ->where('idCalendar = ?', $idCalendar)
                      ->order('startDate');
        try {
            $availbilityRowset = $this->fetchAll($query);

            return $availbilityRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAvailabilityByPk($idAvailability)
    {
        $query = $this->select()
                      ->where('idAvailability = ?', $idAvailability)
                      ->limit(1);
        try {
            $rateRow = $this->fetchRow($query);

            return $rateRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAvailabilityByStartAndEndDate($idCalendar, $startDate, $endDate)
    {
        $query = $this->select()
                      ->where('idCalendar = ?', $idCalendar)
                      ->where('startDate = ?', $startDate)
                      ->where('endDate = ?', $endDate)
                      ->limit(1);
        try {
            $availabilityRow = $this->fetchRow($query);

            return $availabilityRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAvailabilityRangeByCalendarId($idCalendar,
                                                     $startDate=null,
                                                     $endDate=null)
    {
        $this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

        //if (null !== $startDate) {
        //  if (!($startDate instanceof Datetime))
        //      throw new Vfr_Model_Resource_Exception('Wrong data type passed for startDate');
        //}

        //if (null !== $endDate) {
        //  if (!($endDate instanceof Datetime))
        //      throw new Vfr_Model_Resource_Exception('Wrong data type passed for endDate');
        //}

        $query = $this->select()
                      ->where('idCalendar = ?', $idCalendar);

        if ($startDate) {
            $query->where('endDate >= ?', $startDate);
        }

        if ($endDate) {
            $query->where('startDate <= ?', $endDate);
        }

        try {
            $result = $this->fetchAll($query);
        } catch (Exception $e) {
            $profilerQuery = $this->_profiler->getLastQueryProfile();
            $lastQuery = $profilerQuery->getQuery();
            $params = $profilerQuery->getQueryParams();
            $this->_logger->log(__METHOD__ . ' Exception thrown  ' . $e, Zend_Log::ERR);
            $this->_logger->log(__METHOD__ . ' lastQuery  ' . $lastQuery, Zend_Log::ERR);
            $this->_logger->log(__METHOD__ . ' params  ' . implode(',', $params), Zend_Log::ERR);
            $this->_logger->table($table);
            throw $e;
        }

        $this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
        return $result;
    }

    public function getBookedInclusive($idProperty, $startDate, $endDate)
    {
        $this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

        $query = $this->select()
                      ->where('endDate >= ' . $startDate)
                      ->where('startDate <= ' . $endDate)
                      ->where('idProperty = ?', $idProperty);
        try {
            $result = $this->fetchAll($query);
        } catch (Exception $e) {
            throw $e;
        }
        $this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
    }

    public function getBookingCollisions($idCalendar, $startDate, $endDate, $idAvailability=null)
    {
        $startClause = $this->_db->quoteInto('(startDate BETWEEN ?', $startDate)
                     . $this->_db->quoteInto(' AND (? - INTERVAL 1 DAY)', $endDate);

        $endClause   = $this->_db->quoteInto('(endDate - INTERVAL 1 DAY) BETWEEN ?', $startDate)
                     . $this->_db->quoteInto(' AND ?', $endDate);

        $spanClause  = $this->_db->quoteInto('((startDate <= ?)', $startDate)
                     . $this->_db->quoteInto(' AND (endDate >= ?)) )', $endDate);

        try {
            $query = $this->select()
                          ->where('idCalendar = ?', $idCalendar);

            // exclude the row we're editing, if we're in update mode
            if ($idAvailability)
                $query->where('idAvailability != ?', $idAvailability);

            $query->where($startClause)
                  ->orWhere($endClause)
                  ->orWhere($spanClause);

            $availabilityRowset = $this->fetchAll($query);

            return $availabilityRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    //
    // UPDATE
    //

    public function updateAvailabilityByPk($idAvailability, $params)
    {
        $pattern = '/' . Vfr_Form_Element_AvailabilityRangePicker::DELIMITER . '/';
        list ($startDate, $endDate) = preg_split($pattern, $params['availability']);

        $nowExpr  = new Zend_Db_Expr('NOW()');
        $data = array(
            'startDate' => strftime('%Y-%m-%d', strtotime($startDate)),
            'endDate'   => strftime('%Y-%m-%d', strtotime($endDate))
        );

        $adapter = $this->getAdapter();
        $where = $adapter->quoteInto('idAvailability = ?', $idAvailability);
        try {
            $query = $this->update($data, $where);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
