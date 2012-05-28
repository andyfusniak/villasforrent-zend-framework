<?php
class Frontend_Helper_LevelSummary extends Zend_Controller_Action_Helper_Abstract
{
    protected $_propertyModel = null;
    protected $_locationModel = null;

    public function init()
    {
        if (!$this->_propertyModel)
            $this->_propertyModel = new Common_Model_Property();

        if (!$this->_locationModel)
            $this->_locationModel = new Common_Model_Location();
    }


    public function getPropertySummaries($uri, $page)
    {
        $propertyPaginator = $this->_propertyModel->getPropertiesByGeoUri($uri, $page);


        foreach ($propertyPaginator as $item) {
            var_dump($item->idProperty);
        }
        var_dump($propertyPaginator->pagesInRange);
        if ($propertyPaginator->count()) {
            echo count($propertyPaginator);
            die();

            $propertyContent = $this->_propertyModel->getPropertyContentArrayByPropertyList(
                $propertyPaginator,
                Common_Resource_PropertyContent::VERSION_MAIN,
                'EN',
                array (
                    Common_Resource_PropertyContent::FIELD_SUMMARY,
                    Common_Resource_PropertyContent::FIELD_HEADLINE_1
                )
            );

            foreach ($propertyPaginator as $propertyRow) {
                $partials[] = $this->getActionController()->view->partial(
                    'partials/property-summary.phtml',
                    array (
                        'locationRow' => $this->_locationModel->lookup($propertyRow->locationUrl . '/' . $propertyRow->urlName),
                        'photoRow'    => $this->_propertyModel->getPrimaryPhotoByPropertyId($propertyRow->idProperty),
                        'propertyRow'      => $propertyRow,
                        'propertyContent'  => $propertyContent[$propertyRow->idProperty]
                    )
                );
            }

            $this->getActionController()->view->partials       = $partials;
            $this->getActionController()->view->showProperties = true;
        } else {
            $this->getActionController()->view->showProperties = false;
        }
    }

    public function direct($uri, $page)
    {
        $this->getPropertySummaries($uri, $page);
    }
}
