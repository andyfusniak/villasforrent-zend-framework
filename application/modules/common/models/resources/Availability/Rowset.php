<?php
class Common_Resource_Availability_Rowset extends Vfr_Model_Resource_Db_Table_Rowset_Abstract
{
    public function objectSplice($offset, $length)
    {
        $list = $this->_data;
        array_splice($this->_data, $offset, $length);

        // reduce the count by the number we cut away
        $this->_count = $this->_count - $length;
    }
}
