<?php
class Frontend_Model_WebService
{
    private $_idWebService = null;
    private $description;
    private $added;
    private $updated;

    public function __construct($idWebService, $description, $added, $updated)
    {
        $this->_idWebService = (int) $idWebService;
        $this->description = $description;
        $this->added = $added;
        $this->updated = $updated;
    }

    public function setWebServiceId($idWebService)
    {
        $this->_idWebService = (int) $idWebService;
        return $this;
    }

    public function getWebServiceId()
    {
        return $this->_idWebService;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setAdded($added)
    {
        $this->added = $added;
        return $this;
    }

    public function getAdded()
    {
        return $this->added;
    }

    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }
}
