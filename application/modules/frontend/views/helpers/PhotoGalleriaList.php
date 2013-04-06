<?php
class Zend_View_Helper_PhotoGalleriaList extends Zend_View_Helper_Abstract
{
    public function photoGalleriaList($photoRowset, $x, $y)
    {
        $xhtml = '';

        foreach ($photoRowset as $photoRow) {

        }

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
