<?php
class Common_Resource_UrlRedirect extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'UrlRedirects';
    protected $_primary = 'idUrlRedirect';
    protected $_rowClass = 'Common_Resource_UrlRedirect_Row';
    protected $_rowsetClass = 'Common_Resource_UrlRedirect_Rowset';
    //protected $_dependantTables = array();
    //protected $_referenceMap = array ();

    public function createRedirect($incomingUrl, $redirectUrl, $responseCode, $groupName)
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $data = array(
            'idUrlRedirect' => new Zend_Db_Expr('NULL'),
            'incomingUrl'   => $incomingUrl,
            'redirectUrl'   => $redirectUrl,
            'responseCode'  => $responseCode,
            'groupName'     => $groupName,
            'added'         => $nowExpr,
            'updated'       => $nowExpr
        );

        try {
            $this->insert($data);
            $idUrlRedirect = $this->_db->lastInsertId();

            return $idUrlRedirect;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAllPaginator($page, $interval, $order, $direction)
    {
        $query = $this->select()
                      ->order($order . ' ' . $direction);

        $adapter = new Zend_Paginator_Adapter_DbTableSelect($query);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage($interval)
                  ->setCurrentPageNumber($page);

        return $paginator;
    }

    public function searchByPaginator($page, $interval, $order, $direction, $params)
    {
        $query = $this->select()
                      ->order($order . ' ' . $direction);

        $adapter = new Zend_Paginator_Adapter_DbTableSelect($query);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage($interval)
                  ->setCurrentPageNumber($page);

        return $paginator;
    }


    public function lookupIncomingUrl($url)
    {
        $query = $this->select()
                      ->where('incomingUrl = ?', $url)
                      ->limit(1);
        try {
            $urlRedirectRow = $this->fetchRow($query);

            return $urlRedirectRow;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
