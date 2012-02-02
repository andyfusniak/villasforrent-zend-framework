<?php
class Admin_View_Helper_LocationTree extends Zend_View_Helper_Abstract
{
    private function indent($depth=1) {
		$tabs = "";
		for ($i=0; $i<$depth; $i++) {
			$tabs .= "  ";
		}
		
		return $tabs;
	}
    
    public function LocationTree($locationRowset, $includeProperties=false, $prettyPrint=true)
    {
        $xhtml = "<!-- start -->\n";
        $xhtml .= '<div id="tree">' . (($prettyPrint) ? "\n" : '');
        $xhtml .= '<ul><!-- start -->' . (($prettyPrint) ? "\n" : '');
        
        $currentDepth = 0;
        foreach ($locationRowset as $locationRow) {
            //var_dump($currentDepth, $locationRow->depth, $locationRow->rowname, $locationRow->lt, $locationRow->rt);
            
            if ($locationRow->depth > $currentDepth) {
                $currentDepth = $locationRow->depth;
                $xhtml .= $this->indent($currentDepth) . '<ul>' . (($prettyPrint) ? "\n" : '');
                
            } elseif ($locationRow->depth < $currentDepth) {
                $num = $currentDepth - $locationRow->depth;
				
				for ($i=0; $i < $num; $i++) {
                    $xhtml .= $this->indent($currentDepth-$i) . '</ul>' . (($prettyPrint) ? "\n" : '');
                    $xhtml .= $this->indent($currentDepth-$i) . '</li>' . (($prettyPrint) ? "\n\n" : '');	
                }
                
				$currentDepth = $locationRow->depth;
            }
            
            // if a leaf node (close the line element too)
            if ($locationRow->lt == ($locationRow->rt - 1)) {
                $xhtml .= $this->indent($currentDepth)
                       . '<li id="' . $locationRow->idLocation . '" data="idLocation: ' . $locationRow->idLocation
                       . ', addClass: \'context-menu-leaf\'"'
                       . ' class="folder">'
                       . $locationRow->rowname . '(' . $locationRow->totalVisible . ')'
                       . '</li>' . (($prettyPrint) ? "\n" : '');    
            } else {
                $xhtml .= $this->indent($currentDepth+1)
                       . '<li id="' . $locationRow->idLocation . '" '
                       . 'data="hideCheckbox: true, addClass: \'context-menu\'" class="folder">'
                       . $locationRow->rowname . '(' . $locationRow->totalVisible . ')'
                       . (($prettyPrint) ? "\n" : '');
            }        
            
            //echo "<code>" . $xhtml . "</code>";
            //echo "<hr />";
        }
        
        for ($i=$currentDepth; $i > 0; $i--) {
            $xhtml .= $this->indent($i) . '</ul>' . (($prettyPrint) ? "\n" : '');
            $xhtml .= $this->indent($i) . '</li>' . (($prettyPrint) ? "\n" : '');
        }
        
        $xhtml .= '<!-- final --></ul>' . (($prettyPrint) ? "\n" : '');
        $xhtml .= '</div>' . (($prettyPrint) ? "\n" : '');
        
        $xhtml .= "<!-- end -->\n";
        return $xhtml;
    }
}

