<?php
class Common_Model_Location extends Vfr_Model_Abstract
{
    /**
     * @var DOMDocument
     */
    protected $_domDocument;
    protected $_domOptions = array();
    protected $_domAbbrevs = array(
        'idProperty' => 'id',
        'position'   => 'po',
        'startDate'  => 'st',
        'expiryDate' => 'ex',
        'lang'       => 'lg'
    );

    protected $_nodeKeynames = array(
        'name', 'property', 'featured'
    );

    protected $_propertyResource = null;
    protected $_featuredPropertyResource = null;

    public function fillTable()
    {
        return $this->getResource('Location')->fillTable();
    }

    public function xmlDomTree($options = null)
    {
        $this->_domOptions['includePropertyReferences'] = isset($options['includePropertyReferences']) ?: true;
        $this->_domOptions['includeFeaturedReferences'] = isset($options['includeFeaturedReferences']) ?: true;
        $this->_domOptions['abbrevAttributes'] = isset($options['abbrevAttributes']) ?: true;

        if ($this->_domOptions['includePropertyReferences']) {
            if (null === $this->_propertyResource) {
                $this->_propertyResource = $this->getResource('Property');
            }
        }

        if ($this->_domOptions['includeFeaturedReferences']) {
            if (null === $this->_featuredPropertyResource) {
                $this->_featuredPropertyResource = $this->getResource('FeaturedProperty');
            }
        }

        $locationsRowset = $this->getAllLocations();

        if ($locationsRowset) {
            $rootIdLocation = $locationsRowset[0]->idLocation;
        }

        $this->_domDocument = new DOMDocument("1.0", "utf-8");
        $this->_domDocument->formatOutput = true;
        $this->_domDocument->preserveWhiteSpace = false;

        // create root DOMElement
        $rootElement = $this->_createWorldElement($rootIdLocation);
        $this->_domDocument->appendChild($rootElement);

        $currentDepth = 0;
        $currentElement = $rootElement;

        for ($i = 1; $i < sizeof($locationsRowset); $i++) {
            $locationRow = $locationsRowset[$i];

            if ($locationRow->depth > $currentDepth) {
                // create a new element and attach it to the current element
                $element = $this->_domDocument->createElement($locationRow->rowurl);
                $currentElement->appendChild($element);
                $this->_attachNameElement($element, $locationRow->rowname);

                $currentDepth = $locationRow->depth;
                $currentElement = $element;
            } else if ($locationRow->depth < $currentDepth) {
                $num = $currentDepth - $locationRow->depth;
                $currentDepth = $locationRow->depth;
                $currentElement = $currentElement->parentNode;

                $element = $this->_domDocument->createElement($locationRow->rowurl);
                $this->_attachNameElement($element, $locationRow->rowname);

                // go back up the tree N levels
                $ancestorElement = $currentElement->parentNode;
                for ($j=1; $j<$num; $j++) {
                    $ancestorElement = $ancestorElement->parentNode;
                }

                $ancestorElement->appendChild($element);
                $currentElement = $element;
            } else {
                $element = $this->_domDocument->createElement($locationRow->rowurl);
                $this->_attachNameElement($element, $locationRow->rowname);
                $currentElement->parentNode->appendChild($element);
                $currentElement = $element;
            }


            if ($this->_domOptions['includeFeaturedReferences']) {
                $this->_attachFeaturedElements(
                    $element,
                    $locationRow->idLocation
                );
            }

            // if a leaf node
            if ($this->_domOptions['includePropertyReferences']) {
                if ($locationRow->lt == ($locationRow->rt - 1)) {
                    if ($this->_domOptions['includePropertyReferences']) {
                        $this->_attachPropertyElements(
                            $element,
                            $locationRow->idLocation
                        );
                    }
                }
            }

        }

        return $this->_domDocument;
    }

    private function _attribName($name)
    {
        if ($this->_domOptions['abbrevAttributes']) {
            return $this->_domAbbrevs[$name];
        }

        return $name;
    }

    /**
     * @param DOMElement $parentElement the element on which to attach the property elements
     * @param int $idLocation the location which the properties reside
     */
    private function _attachPropertyElements($parentElement, $idLocation)
    {
        $propertyRowset = $this->_propertyResource->getPropertiesInLocation(
            $idLocation
        );

        foreach ($propertyRowset as $propertyRow) {
            $attrib = $this->_domDocument->createAttribute(
                $this->_attribName("idProperty")
            );
            $attrib->value = $propertyRow->idProperty;

            $element = $this->_domDocument->createElement("property");
            $element->appendChild($attrib);
            $parentElement->appendChild($element);
        }

        return $this;
    }

    /**
     * @param DOMElement $parentElement the element on which to attach the featured elements
     * @param int $idLocation the location which the features reside
     */
    private function _attachFeaturedElements($parentElement, $idLocation)
    {
        $featuredPropertyRowset = $this->_featuredPropertyResource->getAllFeaturedPropertiesByLocationId(
            $idLocation
        );

        foreach ($featuredPropertyRowset as $featuredPropertyRow) {
            $attribProperty = $this->_domDocument->createAttribute(
                $this->_attribName("idProperty")
            );
            $attribProperty->value = $featuredPropertyRow->idProperty;

            $attribPosition = $this->_domDocument->createAttribute(
                $this->_attribName("position")
            );
            $attribPosition->value = $featuredPropertyRow->position;

            $attribStartDate = $this->_domDocument->createAttribute(
                $this->_attribName("startDate")
            );
            $attribStartDate->value = $featuredPropertyRow->startDate;

            $attribExpiryDate = $this->_domDocument->createAttribute(
                $this->_attribName("expiryDate")
            );
            $attribExpiryDate->value = $featuredPropertyRow->expiryDate;

            $element = $this->_domDocument->createElement("featured");
            $element->appendChild($attribProperty);
            $element->appendChild($attribPosition);
            $element->appendChild($attribStartDate);
            $element->appendChild($attribExpiryDate);

            $parentElement->appendChild($element);
        }

        return $this;
    }

    /**
     * @param DOMElement $parentElement the parent element to attach this name element to
     * @param string $name the utf-8 text string
     */
    private function _attachNameElement($parentElement, $name)
    {
        $attrib = $this->_domDocument->createAttribute(
            $this->_attribName("lang")
        );
        $attrib->value = "en";

        $element = $this->_domDocument->createElement("name", $name);
        $element->appendChild($attrib);
        $parentElement->appendChild($element);
    }

    private function _createWorldElement($idLocation)
    {
        $attrib = $this->_domDocument->createAttribute("visible");
        $attrib->value = "false";

        $element = $this->_domDocument->createElement("world");
        $element->appendChild($attrib);

        $nameElement = $this->_domDocument->createElement("name", "World");

        $nameAttrib = $this->_domDocument->createAttribute($this->_attribName("lang"));
        $nameAttrib->value = "en";
        $nameElement->appendChild($nameAttrib);

        // attach the name node to the world element
        $element->appendChild($nameElement);

        // attach the featured properties to the root node of the tree
        // if requested
        if ($this->_domOptions['includeFeaturedReferences']) {
            $this->_attachFeaturedElements(
                $element,
                $idLocation
            );
        }

        return $element;
    }

    /**
     * Returns true if the node is an element node and not a key name node
     * i.e. is part of the location hierarchy and not meta structure
     *
     * @param DOMNode $node a node in the overall tree
     * @return bool returns true if DOMElement and not a keyname node
     */
    private function _treeStructureElement($node)
    {
        if ($node instanceof DOMElement) {
            if (in_array($node->nodeName, $this->_nodeKeynames))
                return false;

            return true;
        }

        return false;
    }

    private function _elementIsType($node, $type)
    {
        if (($node instanceof DOMElement) && ($type === $node->nodeName)) {
            return true;
        }

        return false;
    }

    /**
     * Given a featured element extracts the attributes and returns a hash map
     * @param DOMElement $node a featured element
     * @return array a hash map of the attributes on this node
     */
    private function _featureArrayFromFeatureElement($node)
    {
        if (($node instanceof DOMElement) && ("featured" === $node->nodeName)) {
            //$this->_nodeDump($node);
            return array(
                'idProperty' => $node->getAttribute($this->_attribName("idProperty")),
                'position'   => $node->getAttribute($this->_attribName("position")),
                'startDate'  => $node->getAttribute($this->_attribName("startDate")),
                'expiryDate' => $node->getAttribute($this->_attribName("expiryDate"))
            );
        } else {
            throw new Exception("Not a DOMElement with a name of <featured>");
        }
    }

    /**
     * Given a property element extracts the attributes and returns a hash map
     * @param DOMElement $node a property element
     * @return array a hash map of the attributes on this node
     */
    private function _propertyArrayFromPropertyElement($node)
    {
        if (($node instanceof DOMElement) && ("property" === $node->nodeName)) {
            //$this->_nodeDump($node);
            $property = array(
                'idProperty' => $node->getAttribute($this->_attribName("idProperty"))
            );

            if ($node->hasAttribute("visible")) {
                $property['visible'] = ("false" === $node->getAttribute("visible")) ? false : $node->getAttribute("visible");
            }

            return $property;
        } else {
            throw new Exception("Not a DOMElement with a name of <featured>");
        }
    }

    private function _hasTreeChildNodes($node)
    {
        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $child) {
                if ($this->_treeStructureElement($child)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Looks for a child DOMElement node called name and gets it's value
     * @param DOMElement $node the tree element node with the name node attached
     * @return string the value of the name node <node><name lang="en">this part</name></node>
     */
    private function _childNameNodeName($node)
    {
        if (($node instanceof DOMElement) && ($node->hasChildNodes())) {
            // the name node isn't necessarily the first one (usually is but
            // doesn't have to be), so we'll search the whole level below for it
            foreach ($node->childNodes as $child) {
                if ("name" === $child->nodeName) {
                    //$this->_nodeDump($child);
                    return $child->textContent;
                }
            }
        }
    }

    private function _nodeTypeToString($type)
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

    private function _nodeDump($node, $eol = "<br/>")
    {
        echo "________________________________________________________________" . $eol;
        echo "Node name: " . $node->nodeName . " = " . $node->textContent . $eol;
        echo "Node type: " . $this->_nodeTypeToString($node->nodeType) . $eol;
        var_dump($node);
        echo "node->hasChildren = " . $node->hasChildNodes() . $eol;
        echo "node->hasAttributes = " . $node->hasAttributes() . $eol;
        if ($node->hasAttributes()) {
            echo "Has " . $node->attributes->length . " attribute(s)" . $eol;

            for ($i=0; $i<$node->attributes->length; $i++) {
                echo "Attribute[" . $i . "] " . $node->attributes->item($i)->name . " = "
                . $node->attributes->item($i)->value . $eol;
            }
        }
        echo "________________________________________________________________" . $eol;
    }

    private function _markParentsRgts($current, $lar)
    {
        $parent = $current->parentNode;

        if ($this->_treeStructureElement($parent)) {
            //print "MARK: " . $parent->nodeName . PHP_EOL;
            $parent->setAttribute("nest-rt", $lar++);
        }

        //$parentsChildren = $parent->childNodes;
        //var_dump($parentsChildren->length);

        $done = false;
        while (!$done) {
            $current = $parent;
            $parent = $parent->parentNode;
            $parentLastChild = $parent->lastChild;

            //print "Last child of " . $parent->nodeName . " is " . $parentLastChild->nodeName . PHP_EOL;

            if ((!$this->_treeStructureElement($parent)) || ($parent->nodeName === "world")) {
                $done = true;
            } else if ((null === $parent->childNodes->length) || ($parentLastChild !== $current)) {
                $done = true;
            } else {
                //var_dump($parent->nodeName);
                //print "MARK " . $parent->nodeName . PHP_EOL;
                $parent->setAttribute("nest-rt", $lar++);
            }

        }

        return $lar;
    }

    public function rebuildDbFromNestedSet($nestedSet)
    {
        $featuredPropertyResource = $this->getResource('FeaturedProperty');

        $locationResource = $this->getResource('Location');
        $locationResource->rebuildDbFromNestedSet(
            $nestedSet,
            $featuredPropertyResource
        );

        $this->updateLocationPropertyTotals();
     }

    public function nestedSetFromTaggedXmlTree($root, $options = null)
    {
        $this->_domOptions['abbrevAttributes'] = isset($options['abbrevAttributes']) ?: true;

        $nestedSet = array();
        $stack = array();

        array_push($stack, $root);

        while (!empty($stack)) {
            $current = array_pop($stack);

            if ($current->hasChildNodes()) {
                //$this->_nodeDump($current);
                $list = array();

                foreach ($current->childNodes as $child) {
                    array_push($list, $child);
                }

                // put them on the stack in reverse order
                $list = array_reverse($list);
                foreach ($list as $item) {
                    array_push($stack, $item);
                }
                unset($list);
            }

            if ($this->_treeStructureElement($current)) {
                //$this->_nodeDump($current);

                $idx = $current->getAttribute("nest-id");

                //if ($current->parentNode instanceof DOMElement)
                //    $nestedSet[$idx]['idParent'] = $current->parentNode->getAttribute("nest-pid");

                $nestedSet[$idx]['pid']     = $current->getAttribute("nest-pid");
                $nestedSet[$idx]['lt']      = $current->getAttribute("nest-lt");
                $nestedSet[$idx]['rt']      = $current->getAttribute("nest-rt");
                $nestedSet[$idx]['depth']   = $current->getAttribute("nest-depth");
                $nestedSet[$idx]['url']     = $current->getAttribute("nest-url");
                $nestedSet[$idx]['name']    = $current->getAttribute("nest-name");
                $nestedSet[$idx]['rowurl']  = $current->getAttribute("nest-rowurl");
                $nestedSet[$idx]['rowname'] = $current->getAttribute("nest-rowname");
                $nestedSet[$idx]['properties'] = array();
                $nestedSet[$idx]['featured'] = array();

                //print $current->nodeName . "<br />";
            } else if ($this->_elementIsType($current, "property")) {
                $nestedSet[$idx]['properties'][] = $this->_propertyArrayFromPropertyElement($current);
            } else if ($this->_elementIsType($current, "featured")) {
                $nestedSet[$idx]['featured'][] = $this->_featureArrayFromFeatureElement($current);
            }
        }

        return $nestedSet;
    }

    public function tagXmlDomTree($root, $options = null)
    {
        $this->_domOptions['abbrevAttributes'] = isset($options['abbrevAttributes']) ?: true;

        if (!isset($options['tagTree'])) {
            $options['tagTree'] = true;
        }

        if (!isset($options['debug'])) {
            $options['debug'] = false;
        }

        $stack = array();
        $depth = 0;
        $index = 1;
        $lar = 1;

        array_push($stack, array($root, $depth, '', ''));

        while (!empty($stack)) {
            list($current, $depth, $url, $name) = array_pop($stack);
            $rowname = $this->_childNameNodeName($current);

            $current->setAttribute("nest-lt", $lar++);
            $current->setAttribute("nest-depth", $depth);
            $current->setAttribute("nest-url", $url);
            $current->setAttribute("nest-name", $rowname);

            if ("world" !== $current->nodeName)
                $current->setAttribute("nest-pid", $current->parentNode->getAttribute("nest-id"));

            if ($options['debug'])
                $this->_nodeDump($current);

            if ($this->_hasTreeChildNodes($current)) {
                $list = array();

                foreach ($current->childNodes as $child) {
                    if ($this->_treeStructureElement($child)) {
                        array_push($list,
                            array(
                                $child,
                                $depth+1,
                                $url . ((strlen($url) > 0) ? '/' : '') . $child->nodeName,
                                $name . ((strlen($name) > 0) ? ' : ' : '') . $this->_childNameNodeName($child),
                                //$name . ' : ' .

                            )
                        );
                    }
                }

                // put them on the stack in reverse order
                $list = array_reverse($list);
                foreach ($list as $item) {
                    array_push($stack, $item);
                }
                unset($list);
            } else {
                // leaf node
                $current->setAttribute("nest-rt", $lar++);

                // mark the rt values for the ancestors upward, if they are a line branch
                if (null === $current->nextSibling)
                    $lar = $this->_markParentsRgts($current, $lar);
            }

            if ($this->_treeStructureElement($current)) {
                $current->setAttribute("nest-id", $index++);
                $current->setAttribute("nest-rowurl", $current->nodeName);
                $current->setAttribute("nest-rowname", $this->_childNameNodeName($current));
                $current->setAttribute("nest-name", $name);
            }

            //$this->_nodeDump($current);
        }

        //if ($this->_treeStructureElement($current)) {
        //    $current->setAttribute("nest-id", $index++);
        //    $current->setAttribute("nest-rowurl", $current->nodeName);
        //    $current->setAttribute("nest-rowname", $this->_childNameNodeName($current));
        //    $current->setAttribute("nest-name", $name);
        //}

        //if ($this->_treeStructureElement($current)) {
        //    $current->setAttribute("nest-id", $index++);
        //}

        // finally do the rgt value for the root node
        $root->setAttribute("nest-rt", $lar);
    }


    /**
     * Writes the given string to the xml dump file in the server data directory
     *
     * @param string $xml the text representation
     */
    public function dumpXmlToServer($xml)
    {
        // get the destination from the configuration
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
        $xmlDumpFileFullPath = $bootstrap['vfr']['xml']['xml_files_dir']
                             . DIRECTORY_SEPARATOR
                             . $bootstrap['vfr']['xml']['xml_dump_filename'];

        if (false === file_put_contents($xmlDumpFileFullPath, $xml, LOCK_EX)) {
            throw new Vfr_Exception("Failed to write out XML data to " . $xmlDumpFileFullPath);
        }

        //  u   g    o
        // -rw- -rw- r--
        chmod($xmlDumpFileFullPath, 0664);
    }

    /**
     * @param int $idParent the id of the root by which to start rebuilding the nested set model
     * @param int $lt  the initial left-value for the nested set
     * @return void
     */
    public function rebuildTree($idParent=null, $lt=0)
    {
        return $this->getResource('Location')->rebuildTree($idParent, $lt);
    }

    public function addLocationToParentFirst($idParentLocation, $name)
    {
        $idParentLocation = (int) $idParentLocation;

        $idParentLocationRow = $this->getLocationByPk($idParentLocation);

        $locationResource = $this->getResource('Location');
        $locationRow = $locationResource->addLocationToParentFirst(
            $idParentLocationRow,
            $name
        );

        return $locationRow;
    }


    //
    // READ
    //

    public function lookup($url)
    {
        return $this->getResource('Location')->lookup($url);
    }

    private function _findNode($dataset, $idParent, $depth)
    {
        $rowset = array();

        foreach($dataset as $row) {
            if (($row->depth == $depth) && ($row->idParent == $idParent)) {
                $rowset[] = $row;
            }
        }

        return $rowset;
    }

    public function getLocationHierarchy()
    {
        $locationRowset = $this->getResource('Location')->getAllLocations();

        return $locationRowset;
    }

    public function getAllLocations()
    {
        return $this->getResource('Location')->getAllLocations();
    }

    public function getLocationList($location)
    {
        return $this->getResource('Location')
                    ->getLocationList(
            $location
        );
    }

    public function getLocationByPk($idLocation)
    {
        $idLocation = (int) $idLocation;

        return $this->getResource('Location')->getLocationByPk($idLocation);
    }

    public function getAllLocationsIn($idLocation=null)
    {
        return $this->getResource('Location')->getAllLocationsIn($idLocation);
    }

    public function getPathFromRootNode($idLocation)
    {
        $idLocation = (int) $idLocation;

        return $this->getResource('Location')->getPathFromRootNode($idLocation);
    }

    public function getPropertiesInLocationCount($idLocation)
    {
        $idLocation = (int) $idLocation;

        $propertyResource = $this->getResource('Property');
        return $propertyResource->getPropertiesInLocationCount(
            $idLocation
        );
    }

    public function getParentIdByChildId($idLocation)
    {
        $idLocation = (int) $idLocation;
        $locationResource = $this->getResource('Location');

        $locationRow = $locationResource->getLocationByPk($idLocation);

        $parentRow = $locationResource->getParentNode($locationRow);

        if ($parentRow)
            return $parentRow->idLocation;
        else
            return null;
    }

    public function areSiblings($idLocationA, $idLocationB)
    {
        $idLocationA = (int) $idLocationA;
        $idLocationB = (int) $idLocationB;

        if ($idLocationA == $idLocationB)
            throw Exception('Location ids do not differ');

        $locationResource = $this->getResource('Location');

        // locate the new records
        $idLocationARow = $this->getLocationByPk(
            $idLocationA
        );
        $idLocationBRow = $this->getLocationByPk(
            $idLocationB
        );

        return $locationResource->haveSameParent(
            $idLocationARow,
            $idLocationBRow
        );
    }

    public function moveLocation($sourceLocationId, $destLocationId, $position=Common_Resource_Location::NODE_BEFORE)
    {
        $sourceLocationId = (int) $sourceLocationId;
        $destLocationId   = (int) $destLocationId;

        $locationResource = $this->getResource('Location');

        // ensure the source and destination are siblings
        $sourceLocationRow = $this->getLocationByPk(
            $sourceLocationId
        );

        $destLocationRow = $this->getLocationByPk(
            $destLocationId
        );

        if (! $locationResource->haveSameParent(
                    $sourceLocationRow,
                    $destLocationRow
           ))
            throw new Vfr_Exception_Locations_NotSiblings(
                'Cannot move nodes that are not siblings'
            );


        $locationResource->moveNode(
            $sourceLocationRow,
            $destLocationRow,
            $position
        );
    }

    public function updateLocationPropertyTotals()
    {
        $locationResource = $this->getResource("Location");
        $propertyResource = $this->getResource("Property");

        $locationRowset = $this->getAllLocations();

        foreach($locationRowset as $locationRow) {
            $totalVisible = $propertyResource->getPropertiesCountByGeoUri(
                $locationRow->url,
                true
            );

            $total = $propertyResource->getPropertiesCountByGeoUri(
                $locationRow->url,
                false
            );

            $locationResource->updateTotals(
                $locationRow->idLocation,
                $totalVisible,
                $total
            );
        }
    }

    //
    // DELETE
    //

    public function deleteLocation($idLocation)
    {
        $idLocation = (int) $idLocation;
        $locationResource = $this->getResource('Location');

        try {
            $locationRow = $this->getLocationByPk($idLocation);
            if ($locationRow) {
                // make sure this node is a leaf node
                if ($locationRow->lt != $locationRow->rt - 1)
                    throw new Vfr_Exception_Location_NotALeafNode(
                        'Location node ' . $idLocation . ' is not a leaf node'
                    );

                // ensure there are no properties in this location
                $numProperties = $this->getPropertiesInLocationCount($idLocation);
                if ($numProperties > 0)
                    throw new Vfr_Exception_Location_LeafNodeHasProperties(
                        'Location node ' . $idLocation . ' still contain ' . $numProperties. ' properties'
                    );
            } else {
                throw new Vfr_Exception_Location_NodeNotFound(
                    'Location node id ' . $idLocation . ' could not be found'
                );
            }

            $locationResource->deleteLeafNode(
                $locationRow
            );

            // not yet implemented, but needs to disallow
            // deleting non-leaf nodes
            // disallow deleting nodes in which properties
            // already reside
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function purgeLocationsTable()
    {
        return $this->getResource('Location')->purgeLocationsTable();
    }

    // Utils
    public static function convertLocationNameToUrl($name)
    {
        $newname = strtolower($name);
        $newname = str_replace(' ', '-', $newname);
        $newname = str_replace('---', '-', $newname);
        $newname = str_replace('--', '-', $newname);

        return $newname;
    }
}
