<?php
class LevelController extends Zend_Controller_Action
{
    protected $_fastLookupModel;
    protected $_locationModel;
    protected $_propertyModel;

    protected $_featuredConfig;

    public function init()
    {
        $this->_locationModel = new Common_Model_Location();
        $this->_propertyModel = new Common_Model_Property();

        // get the destination from the configuration
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
        $this->_featuredConfig = $bootstrap['vfr']['featured'];
    }

    public function listAction()
    {
        $request = $this->getRequest();

        $uri = $request->getParam('uri');
        $locationRow = $this->_locationModel->lookup($uri);
        if (!$locationRow) {
            $this->getResponse()->setHttpResponseCode(404);
            return;
        }

        // get the list of regions within this country
        $locationRowset = $this->_locationModel->getAllLocationsIn(
            $locationRow->idLocation
        );

        $propertyPaginator = $this->_propertyModel->getPropertiesByGeoUri(
            $uri,
            $request->getParam('page', 1)
        );

        $locationContentMapper = new Frontend_Model_LocationContentMapper();
        $locationContentObj = $locationContentMapper->getLocationContentByLocationId(
            $locationRow->idLocation,
            'EN'
        );

        // get the summary for this level
        //$this->_helper->levelSummary($locationRow->url, $page=1);

        // get the featured properties
        $this->_helper->featuredProperty($locationRow->idLocation);

        // Set the title of the page
        $this->view->headTitle(
            $locationRow->name,
            Zend_View_Helper_Placeholder_Container_Abstract::SET
        );

        // Set the description of the page
        $this->view->headMeta("Villas, chalets and apartments to rent in "
            . $locationRow->name, 'description'
        );

        // pass results to the view
        $this->view->assign(
            array(
                'locationRow'        => $locationRow,
                'locationRowset'     => $locationRowset,
                'locationContentObj' => $locationContentObj,
                'locationModel'      => $this->_locationModel,
                'propertyModel'      => $this->_propertyModel,
                'propertyPaginator'  => $propertyPaginator
            )
        );
    }
}
