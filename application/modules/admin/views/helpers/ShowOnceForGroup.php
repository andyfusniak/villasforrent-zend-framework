<?php
class Admin_View_Helper_ShowOnceForGroup extends Zend_View_Helper_Abstract
{
    private $_id = null;
    
    public function showOnceForGroup($id)
    {
        if ($this->_id == null) {
            $this->_id = $id;
            return $id;
        }
        
        if (($this->_id) && ($id == $this->_id)) {
            return '';
        } else {
            $this->_id = $id;
            return $id;
        }
    }
}