<?php
class Frontend_Helper_FeaturedProperty extends Zend_Controller_Action_Helper_Abstract
{
    public function init() {}
    
    public function getFeaturedProperties($idLocation, $limit=null)
    {
        // get the homepage featured properties
        $propertyModel 	= new Common_Model_Property();
		$featuredPropertyRowset = $propertyModel->getFeaturedProperties($idLocation, $limit);
        
		
		
        if ($featuredPropertyRowset->count()) {
            $featuredPropertyContent = $propertyModel->getPropertyContentArrayByPropertyList(
				$featuredPropertyRowset,
                Common_Resource_PropertyContent::VERSION_MAIN,
				'EN',
                array (
                    Common_Resource_PropertyContent::FIELD_HEADLINE_1,
                    Common_Resource_PropertyContent::FIELD_HEADLINE_2
				)
			);
			
            $locationModel = new Common_Model_Location();
            
            $partials = array ();
            foreach ($featuredPropertyRowset as $featuredPropertyRow) {
				$propertyRow = $propertyModel->getPropertyById($featuredPropertyRow->idProperty);
	
                $partials[] = $this->getActionController()->view->partial(
					'partials/featured-property.phtml',
					array (
						'locationRow'	=> $locationModel->getLocationByPk($propertyRow->idLocation),
                        'photoRow'		=> $propertyModel->getPrimaryPhotoByPropertyId($featuredPropertyRow->idProperty),
                        'featuredProperty' => $propertyRow,
                        'featuredContent'  => $featuredPropertyContent[$featuredPropertyRow->idProperty]
					)
				);
            }
        
            $numFeatured = sizeof($partials);
            for ($i=$numFeatured; $i < 3; $i++) {
                $partials[$i] = '&nbsp;';
            }
            
            $this->getActionController()->view->featuredPartials = $partials;
            $this->getActionController()->view->showFeatured     = true;
        } else {
            $this->getActionController()->view->showFeatured = false;
        }
    }
    
    public function direct($mask=Common_Resource_Property::FEATURE_MASK_HOMEPAGE, $limit=3, $uri='')
    {
        $this->getFeaturedProperties($mask, $limit, $uri);
    }
}