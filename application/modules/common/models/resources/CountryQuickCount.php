<?php
class Common_Resource_CountriesQuickCount extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_Countries_Interface
{
	protected $_name = 'CountriesQuickCount';
	protected $_primary = 'idCountry';
	protected $_rowClass = 'Common_Resource_CountryQuickCount_Row';
	protected $_rowsetClass = 'Common_Resource_CountryQuickCount_RowSet';
}
