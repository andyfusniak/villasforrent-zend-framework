<?php
class Zend_View_Helper_SectionTitle extends Zend_View_Helper_Abstract
{
    public function sectionTitle($title, $content=null)
    {
        if (mb_strlen($title) == 0)
            return '';

        if (is_string($content))
            $content = array ($content);

        $xhtml = '';

        $sectionHasContent = false;
        
        if ($content) {
            foreach ($content as $c) {
                if (mb_strlen($c) > 0) {
                    $sectionHasContent = true;
                    $xhtml .= '<p>' . nl2br($this->view->escape($c)) . '</p>';
                }
            }
        }
        
        if ($sectionHasContent) {
            $xhtml = '<h3>' . $this->view->escape($title) . '</h3>' . $xhtml;
        }
        
        return $xhtml;
    }
}
