<?php
$xmlDoc = new DOMDocument();
$xmlDoc->preserveWhiteSpace = false;
$xmlDoc->load("output.xml");
$rootNode = $xmlDoc->documentElement;

$keywords = array(
    'name', 'property', 'featured'
);


//preorderRecursive($rootNode, 1);
$nestedSet = preorderStackTraversal($rootNode, array(
    'tagTree' => true,
    'debug'   => false
));

nestedSet($rootNode);

/*
 * kept for comparison of algorithms
function preorderRecursive($root, $depth)
{
    global $keywords;

    $nodeName = $root->nodeName;

    if (!in_array($nodeName, $keywords)) {
        print $depth . ' ' . $nodeName . PHP_EOL;
    }

    foreach ($root->childNodes as $node) {
        // for pre-order reverse the child order (right to left)
        if ($node instanceof DOMElement) {
            preorderRecursive($node, $depth+1);
        }
    }
}
*/

function nestedSet($root)
{
    $nestedSet = array();
    $stack = array();

    array_push($stack, $root);

    while (!empty($stack)) {
        $current = array_pop($stack);

        if ($current->hasChildNodes()) {
            $list = array();
            foreach ($current->childNodes as $child) {
                if (treeStructureElement($child)) {
                    array_push($list, $child);
                }
            }

            // put them on the stack in reverse order
            $list = array_reverse($list);
            foreach ($list as $item) {
                array_push($stack, $item);
            }
            unset($list);
        }

        if (treeStructureElement($current)) {
            print $current->getAttribute("nest-id") . " "
                . $current->getAttribute("nest-pid") . " "
                . $current->getAttribute("nest-url") . " "
                . "d=" . $current->getAttribute("nest-depth") . " "
                . $current->getAttribute("nest-lft") . " "
                . $current->getAttribute("nest-rgt") . " "
                . $current->nodeName . PHP_EOL;
        }
    }
}

function markParentsRgts($current, $lar)
{
    $parent = $current->parentNode;
    //_nodeDump($parent);

    if (treeStructureElement($parent)) {
        //print "MARK: " . $parent->nodeName . PHP_EOL;
        $parent->setAttribute("nest-rgt", $lar++);
    }

    //$parentsChildren = $parent->childNodes;
    //var_dump($parentsChildren->length);

    $done = false;
    while (!$done) {
        $current = $parent;
        $parent = $parent->parentNode;
        $parentLastChild = $parent->lastChild;

        //print "Last child of " . $parent->nodeName . " is " . $parentLastChild->nodeName . PHP_EOL;

        if ((!treeStructureElement($parent)) || ($parent->nodeName === "world")) {
            $done = true;
        } else if ((null === $parent->childNodes->length) || ($parentLastChild !== $current)) {
            $done = true;
        } else {
            //var_dump($parent->nodeName);
            //print "MARK " . $parent->nodeName . PHP_EOL;
            $parent->setAttribute("nest-rgt", $lar++);
        }


    }

    return $lar;
}

function treeStructureElement($element)
{
    global $keywords;

    if ($element instanceof DOMElement) {
        if (in_array($element->nodeName, $keywords)) {
            //die('no way');
            return false;
        }

        return true;
    }

    return false;
}

function hasTreeChildNodes($node)
{
    if ($node->hasChildNodes()) {
        foreach ($node->childNodes as $child) {
            if (treeStructureElement($child)) {
                return true;
            }
        }
    }

    return false;
}

function _nodeTypeToString($type)
{
    switch ($type) {
        case 1:
            return "XML_ELEMENT_NODE";
            break;
        case 2:
            return "XML_ATTRIBUTE_NODE";
            break;
        case 3:
            return "XML_TEXT_NODE";
            break;
        case 4:
            return "XML_CDATA_SECTION_NODE";
            break;
        case 5:
            return "XML_ENTITY_REF_NODE";
            break;
        case 6:
            return "XML_ENTITY_NODE";
            break;
        case 7:
            return "XML_PI_NODE";
            break;
        case 8:
            return "XML_COMMENT_NODE";
            break;
        case 9:
            return "XML_DOCUMENT_NODE";
            break;
        case 10:
            return "XML_DOCUMENT_TYPE_NODE";
            break;
        default:
            return $type;
    }
}

function _nodeDump($node)
{
    echo "________________________________________________________________" . PHP_EOL;
    echo "Node name: " . $node->nodeName . " = " . $node->value . PHP_EOL;
    echo "Node type: " . _nodeTypeToString($node->nodeType) . PHP_EOL;
    var_dump($node);
    echo "node->hasChildren = " . $node->hasChildNodes() . PHP_EOL;
    echo "node->hasAttributes = " . $node->hasAttributes() . PHP_EOL;
    if ($node->hasAttributes()) {
        echo "Has " . $node->attributes->length . " attribute(s)" . PHP_EOL;

        for ($i=0; $i<$node->attributes->length; $i++) {
            echo "Attribute[" . $i . "] " . $node->attributes->item($i)->name . " = "
            . $node->attributes->item($i)->value . PHP_EOL;
        }
    }
    echo "________________________________________________________________" . PHP_EOL;
}

function preorderStackTraversal($root, $options)
{

    if (!isset($options['tagTree'])) {
        $options['tagTree'] = true;
    }

    if (!isset($options['debug'])) {
        $options['debug'] = false;
    }

    $nestedSet = array();

    $depth = 1;
    $index = 1;
    $lar = 1;

    $stack = array();

    array_push($stack, array($root, $depth, ''));

    while (!empty($stack)) {
        list($current, $depth, $url) = array_pop($stack);

        $nestedSet[$index] = array(
            'idParent' => null,
            'lft'      => $lar,
            'rgt'      => null,
            'depth'    => $depth,
            'rowurl'   => null,
        );

        if ($options['tagTree']) {
            $current->setAttribute("nest-lft", $lar);
            $current->setAttribute("nest-depth", $depth);
            $current->setAttribute("nest-url", $url);

            if ("world" !== $current->nodeName) {
                $nestedSet[$index]['idParent'] = (int) $current->parentNode->getAttribute("nest-id");

                if ($options['tagTree'])
                    $current->setAttribute("nest-pid", $current->parentNode->getAttribute("nest-id"));
            }
        }

        $lar++;

        if ($options['debug'])
            $this->_nodeDump($current);

        if (hasTreeChildNodes($current)) {
            $list = array();

            foreach ($current->childNodes as $child) {
                if (treeStructureElement($child))
                    array_push($list, array($child, $depth+1,  $url . ((strlen($url) > 0) ? '/' : '') . $child->nodeName));
            }

            // put them on the stack in reverse order
            $list = array_reverse($list);
            foreach ($list as $item) {
                array_push($stack, $item);
            }

            unset($list);
        } else {
            // leaf node

            if ($options['tagTree']) {
                $current->setAttribute("nest-rgt", $lar);

                $lar++;

                // mark the rgt values for the ancestors upward, if they are a line branch
                if (null === $current->nextSibling)
                    $lar = markParentsRgts($current, $lar);

            }
        }

        if (treeStructureElement($current)) {
            print $current->nodeName;

            if ($options['tagTree'])
                $current->setAttribute("nest-id", $index);

            $index++;
        }
    }

    // finally do the rgt value for the root node
    if ($options['tagTree'])
        $root->setAttribute("nest-rgt", $lar);

    return $nestedSet;
}
