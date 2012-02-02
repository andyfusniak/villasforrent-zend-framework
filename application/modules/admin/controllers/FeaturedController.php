<?php
class Admin_FeaturedController extends Zend_Controller_action
{
    public function listByLocationAction()
    {
        
    }
    
    public function listByPropertyAction()
    {
        $propertyModel = new Common_Model_Property();
        
        $request = $this->getRequest();
		$page      = $request->getParam('page');
		$interval  = $request->getParam('interval');
		$order     = $request->getParam('order');
		$direction = $request->getParam('direction');
    
        $featuredPropertiesPaginator = $propertyModel->getAllFeaturedPropertiesPaginator(
			$page,
			$interval,
			$order,
			$direction
		);
        
        $session = new Zend_Session_Namespace(Common_Model_Property::SESSION_NS_ADMIN_PROPERTY_LIST_BY_PROPERTY);
        
        $this->view->assign(
			array (
				'featuredPropertiesPaginator' => $featuredPropertiesPaginator,
				'order'				          => isset($session->order) ? $session->order : $order,
				'direction'		              => isset($session->direction) ? $session->direction : $direction
			)
		);
    }
}