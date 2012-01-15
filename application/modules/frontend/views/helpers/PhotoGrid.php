<?php
class Zend_View_Helper_PhotoGrid extends Zend_View_Helper_Abstract
{
    public function photoGrid($photoRowset, $x, $y)
    {
        $xhtml = '';
        $pair = '<div class="yui-g"><div class="yui-u first">%A</div><div class="yui-u">%B</div></div>';
        
        $count = sizeof($photoRowset);
        if ($count == 0)
            return '';
        
        $i = 0;
        while ($i < $count) {
            $htmlLine = $pair;
            
            $rowA = $photoRowset[$i];
            $htmlLine = str_replace("%A", $this->view->photo($rowA, $x, $y) . '<p>' . $rowA->caption . '</p>', $htmlLine);
       
            if ($i < $count-1) {
                $rowB = $photoRowset[$i+1];
                $htmlLine = str_replace("%B", $this->view->photo($rowB, $x, $y) . '<p>' . $rowB->caption . '</p>', $htmlLine);
            } else {
                $htmlLine = str_replace("%B", "&nbsp;", $htmlLine);
            }
            
            $xhtml .= $htmlLine;
            $i = $i + 2;
        }
        
        return $xhtml;
    }
}