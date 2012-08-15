<?php
class Frontend_Model_FeaturedMapper extends Frontend_Model_MapperAbstract
{
    /**
     * Adds a new featured property
     *
     * @param Frontend_Model_Featured $featuredObj the featured property object
     * @return int the primary key of the new DB row
     */
    public function addFeatured(Frontend_Model_Featured $featuredObj)
    {
        $featuredResource = $this->getResource('Featured');

        $idFeaturedProperty = $featuredResource->featureProperty(
            $featuredObj->getPropertyId(),
            $featuredObj->getLocationId(),
            $featuredObj->getStartDate(),
            $featuredObj->getExpiryDate(),
            $featuredObj->getPosition()
        );

        return $idFeaturedProperty;
    }

    public function saveFeatured(Frontend_Model_Featured $featuredObj)
    {
        $featuredResource = $this->getResource('Featured');

        $featuredResource->updateFeaturedProperty(
            $featuredObj->getFeaturedPropertyId(),
            $featuredObj->getPropertyId(),
            $featuredObj->getLocationId(),
            $featuredObj->getStartDate(),
            $featuredObj->getExpiryDate(),
            $featuredObj->getPosition()
        );
    }
}
