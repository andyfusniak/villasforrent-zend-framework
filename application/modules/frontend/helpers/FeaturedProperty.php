<?php
class Frontend_Helper_FeaturedProperty extends Zend_Controller_Action_Helper_Abstract
{
    public function init() {}
    
    public function getFeaturedProperties($mask, $limit, $uri)
    {
        // get the homepage featured properties
        $propertyModel 	= new Common_Model_Property();
		$featuredPropertyRowset = $propertyModel->getFeaturedProperties($mask, $limit, $uri);
        
        if ($featuredPropertyRowset->count()) {
            $featuredPropertyContent = $propertyModel->getPropertyContentArrayByPropertyList($featuredPropertyRowset,
                                                                                         Common_Resource_PropertyContent::VERSION_MAIN,
                                                                                         'EN',
                                                                                         array(
                                                                                         Common_Resource_PropertyContent::FIELD_HEADLINE_1,
                                                                                         Common_Resource_PropertyContent::FIELD_HEADLINE_2));
            //$this->view->countryRowset = $countryRowset;
            
            $locationModel = new Common_Model_Location();
            
            $partials = array ();
            foreach ($featuredPropertyRowset as $featuredPropertyRow) {
                //var_dump($featuredPropertyRow);
                
                $partials[] = $this->getActionController()->view->partial('partials/featured-property.phtml', array(
                                                            'locationRow'	   => $locationModel->lookup($featuredPropertyRow->locationUrl . '/' . $featuredPropertyRow->urlName),
                                                            'photoRow'		   => $propertyModel->getPrimaryPhotoByPropertyId($featuredPropertyRow->idProperty),
                                                            'featuredProperty' => $featuredPropertyRow,
                                                            'featuredContent'  => $featuredPropertyContent[$featuredPropertyRow->idProperty]));
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