<?php
class Frontend_Model_LocationContentBody
{
    private $content;
    private $priority;
    private $lang;

    public function __construct($content = null, $priority = 1, $lang = 'EN')
    {
        $this->content  = $content;
        $this->priority = (($priority == null) ? (int) 1 : (int) $priority);
        $this->lang     = $lang;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setPriority($priority)
    {
        $this->priority = (($priority == null) ? 1 : (int) $priority);
        return $this;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setLanguage($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    public function getLanguage()
    {
        return $this->lang;
    }
}
