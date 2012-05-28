<?php
class Common_Resource_Administrator extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'advertisers';
    protected $_primary = 'idAdvertiser';
    protected $_rowClass = 'Common_Resource_Administrator_Row';
    protected $_rowsetClass = 'Common_Resource_Administrator_Rowset';
}
