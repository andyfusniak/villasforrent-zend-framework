<?php
class Frontend_Model_LocationContent
{
    private $_idLocationContent = null;

    private $uri;
    private $images = array();
    private $headings = array();
    private $bodyObjects = array();
    private $bodys = array();


    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function addImage(Frontend_Model_LocationContentImage $obj)
    {
        array_push($this->images, $obj);
        return $this;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function setHeading($text, $lang = 'EN')
    {
        $this->headings[$lang] = $text;
        return $this;
    }

    public function getHeadings()
    {
        return $this->headings;
    }

    public function getHeading($lang = 'EN')
    {
        if (isset($this->headings[$lang]))
            return $this->headings[$lang];

        return null;
    }

    public function addBody(Frontend_Model_LocationContentBody $obj)
    {
        $lang = $obj->getLanguage();
        $priority = $obj->getPriority();

        $this->bodys[$lang][$priority] = $obj;
        array_push($this->bodyObjects, $obj);
    }

    public function getAllBodys()
    {
        return $this->bodyObjects;
    }

    public function getBody($lang, $priority)
    {
        if (isset($this->bodys[$lang][$priority]))
            return $this->bodys[$lang][$priority];

        throw new Exception("Body of lang $lang with priority $priority does not exist");
    }
}
