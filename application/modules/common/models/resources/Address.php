<?php
class Common_Resource_Address extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'Addresses';
    protected $_primary = 'idAddress';
    protected $_rowClass = 'Common_Resource_Address_Row';
    protected $_rowsetClass = 'Common_Resource_Address_Rowset';

    /**
     * Add a new address
     *
     * @param string $name the company or contact name
     * @param string $line1 the first line of the address
     * @param string $line2 the second line of the address
     * @param string $line3 the third line of the address
     * @param string $townCity the town or city
     * @param string $county the county or state
     * @param string $postcode post code or zip code
     * @param string $country the country e.g. UK, Thailand etc
     * @return int the address id (last insert id from the DB)
     */
    public function addAddress($name, $line1, $line2, $line3, $townCity, $county, $postcode, $country)
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $data = array(
            'idAddress' => new Zend_Db_Expr('NULL'),
            'name'      => $name,
            'line1'     => $line1,
            'line2'     => $line2,
            'line3'     => $line3,
            'townCity'  => $townCity,
            'county'    => $county,
            'postcode'  => $postcode,
            'country'   => $country,
            'added'     => $nowExpr,
            'updated'   => $nowExpr
        );

        try {
            $this->insert($data);
            $idAddress = $this->_db->lastInsertId();

            return $idAddress;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get an address by address id (primary key)
     *
     * @param int $idAddress the address id (primary key)
     *
     * @return Common_Resource_Address_Row
     */
    public function getAddressByAddressId($idAddress)
    {
        $query = $this->select()
                      ->where('idAddress = ?', (int) $idAddress);
        try {
            $addressRow = $this->fetchRow($query);

            return $addressRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateAddress($idAddress, $params)
    {
        $params['update'] = new Zend_Db_Expr('NOW()');

        $adapter = $this->getAdapter();
        if ($status) {
            $where = $adapter->quoteInto('idAddress = ?', (int) $idAddress);
        } else {
            $where = $adapter->quoteInto('idAddress = ?', (int) $idAddress);
        }

        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
