<?php
class Common_Resource_WebProduct extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'WebProducts';
    protected $_primary = 'idWebProduct';
    protected $_rowClass = 'Common_Resource_WebProduct_Row';
    protected $_rowsetClass = 'Common_Resource_WebProduct_Rowset';


    /**
     * Add a new web product
     *
     * @param string $productCode the product code
     * @param string name a name to be copied to appear on the invoice line
     * @param float $unitPrice the unit price of the product
     * @param string $repeats repeat frequency e.g. YEARLY, MONTHLY, WEEKLY
     * @param string $description detail of this product
     * @return int the web-product id (last insert id)
     */
    public function addWebProduct($productCode, $name, $unitPrice, $repeats, $description)
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $data = array(
            'idWebProduct' => new Zend_Db_Expr('NULL'),
            'productCode'  => $productCode,
            'name'         => $name,
            'unitPrice'    => (float) $unitPrice,
            'repeats'      => $repeats,
            'description'  => $description,
            'added'        => $nowExpr,
            'updated'      => $nowExpr
        );

        try {
            $this->insert($data);
            $idWebProduct = $this->_db->lastInsertId();

            return $idWebProduct;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get a web-product by web-product id (primary key)
     *
     * @param int $idWebProduct the web-product id (primary key)
     * @return Common_Resource_WebProduct_Row
     */
    public function getWebProductByWebProductId($idWebProduct)
    {
        $query = $this->select()
                      ->where('idWebProduct = ?', (int) $idWebProduct);
        try {
            $webProductRow = $this->fetchRow($query);

            return $webProductRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get an entire list of the web-products
     *
     * @return Common_Resource_WebProduct_Rowset
     */
    public function getAllWebProducts()
    {
        $query = $this->select()
                      ->order('idWebProduct ASC');
        try {
            $webProductRowset = $this->fetchAll($query);

            return $webProductRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }





}