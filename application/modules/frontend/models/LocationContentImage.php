<?php
class Frontend_Model_LocationContentImage
{
    private $uri;
    private $priority;
    private $captions = array();

    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setPriority($priority)
    {
        $this->priority = (int) $priority;
        return $this;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setCaption($caption, $lang = 'EN')
    {
        $this->captions[$lang] = $caption;
        return $this;
    }

    public function getCaption($lang = 'EN')
    {
        if (isset($this->captions[$lang]))
            return $this->captions[$lang];

        return null;
    }

    public function getCaptions()
    {
        return $this->captions;
    }
}
