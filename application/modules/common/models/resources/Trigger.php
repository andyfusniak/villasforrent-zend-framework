<?php
class Common_Resource_Trigger extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'information_schema.triggers';
    protected $_primary = 'TRIGGER_NAME';
    protected $_rowClass = 'Common_Resource_Trigger_Row';
    protected $_rowsetClass = 'Common_Resource_Trigger_Rowset';

    public function getAll()
    {
        try {
            $query = $this->select();
            $triggerRowset = $this->fetchAll($query);

            return $triggerRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getByName($name)
    {
        $query = $this->select()
                      ->where('TRIGGER_NAME = ?', $name);
        try {
            $triggerRow = $this->fetchRow($query);

            return $triggerRow;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
