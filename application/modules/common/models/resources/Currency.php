<?php
class Common_Resource_Currency extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'Currencies';
    protected $_primary = 'iso3char';
    protected $_rowClass = 'Common_Resource_Currency_Row';
    protected $_rowsetClass = 'Common_Resource_Currency_Rowset';
    protected $_dependentTables = array('Calendars');

    public function getAllCurrencies($visible, $inUse)
    {
        $query = $this->select()
                      ->where('visible = ?', $visible)
                      ->where('inUse = ?', $inUse)
                      ->order('displayPriority');
        try {
            $currencyRowset = $this->fetchAll($query);
            return $currencyRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
