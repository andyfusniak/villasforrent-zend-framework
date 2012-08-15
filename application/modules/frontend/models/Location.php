<?php
class Frontend_Model_Location
{
    /**
     * @var int
     */
    private $idLocation = null;

    /**
     * @var int
     */
    private $idParent;

    /**
     * @var string
     */
    private $url;

    /**
     * @var int
     */
    private $lt;

    /**
     * @var int
     */
    private $rt;

    /**
     * @var int
     */
    private $depth;

    /**
     * @var string
     */
    private $rowurl;

    /**
     * @var int
     */
    private $totalVisible;

    /**
     * @var int
     */
    private $total;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $rowname;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $postfix;

    /**
     * @var int
     */
    private $visible;

    /**
     * @var string
     */
    private $added;

    /**
     * @var string
     */
    private $updated;

    /**
     * Constructor for Location object
     * @return Frontend_Model_Location
     */
    public function __construct($idLocation, $idParent, $url, $lt, $rt, $depth,
            $rowurl, $totalVisible, $total, $name, $rowname, $prefix, $postfix,
            $visible, $added, $update)
    {
        $this->idLocation = (int) $idLocation;
        $this->idParent = (int) $idParent;
        $this->url = $url;
        $this->lt = (int) $lt;
        $this->rt = (int) $rt;
        $this->depth = (int) $depth;
        $this->rowurl = $rowurl;
        $this->totalVisible = (int) $totalVisible;
        $this->total = (int) $total;
        $this->name = $name;
        $this->rowname = $rowname;
        $this->prefix = $prefix;
        $this->postfix = $postfix;
        $this->visible = $visible;
        $this->added = $added;
        $this->updated = $updated;
    }

    /**
     * Set the id location for this Location object
     * @param int $idLocation the location id for this Location object
     * @return Frontend_Model_Location
     */
    public function setIdLocation($idLocation)
    {
        $this->idLocation = $idLocation;
        return $this;
    }

    /**
     * Returns the location id of this Location object
     * @return int the location id
     */
    public function getIdLocation()
    {
        return $this->idLocation;
    }
}
