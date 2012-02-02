<?php
class Zend_View_Helper_TitleSection extends Zend_View_Helper_Abstract
{
    public function titleSection($title, $content)
    {
        if (mb_strlen($title) == 0)
            return '';

        if (is_string($content)) {
            $content = array ($content);

        $xhtml = '';

        $sectionHasContent = false;
        foreach ($content as $c) {
            if (mb_strlen($c) > 0) {
                $sectionHasContent = true;
                $xhtml .= '<p>' . $this->view->escape($c) . '</p>';
            }
        }

        if ($sectionHasContent) {
            $xhtml = '';
        }
        }
    }
}
