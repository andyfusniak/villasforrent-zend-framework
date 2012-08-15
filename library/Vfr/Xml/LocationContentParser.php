<?php
class Vfr_Xml_LocationContentParser extends Vfr_Xml_ParserAbstract
{
    /**
     * Singleton instance
     *
     * @return void
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function createLocationContent()
    {
        $obj = new Frontend_Model_LocationContent();

        // get the root node
        $locationContentElement = $this->_domDocument->getElementsByTagName('location-content')->item(0);
        if (null === $locationContentElement)
            throw new Exception("This file is not a location-content XML file");

        // get the uri of this file
        $mainUriElement = $locationContentElement->getElementsByTagName('uri');
        $obj->setUri($mainUriElement->item(0)->nodeValue);

        // find all image elements
        $imageElements = $locationContentElement->getElementsByTagName('image');

        foreach ($imageElements as $imgElement) {
            $imgObj = new Frontend_Model_LocationContentImage();

            // get the image priority
            $imgObj->setPriority($imgElement->getAttribute('pri'));

            // get the URI to the image file
            $uriElements = $imgElement->getElementsByTagName('uri');

            if ($uriElements->length !== 1)
                throw new Exception("There should be only one <uri> element with each <image> element");

            $imgObj->setUri($uriElements->item(0)->nodeValue);

            // read the captions (there can be multiple languages)
            $captionElements = $imgElement->getElementsByTagName('caption');
            foreach ($captionElements as $capElement) {
                $imgObj->setCaption($capElement->nodeValue, $capElement->getAttribute('lg'));
            }

            $obj->addImage($imgObj);
            unset($imgObj);
        }

        // find all the headings
        $headingElements = $locationContentElement->getElementsByTagName('heading');
        foreach ($headingElements as $headingElement) {
            $obj->setHeading($headingElement->nodeValue, $headingElement->getAttribute('lg'));
        }

        // find all the content bodys
        $bodyElements = $locationContentElement->getElementsByTagName('body');
        foreach ($bodyElements as $bodyElement) {
            $bodyObj = new Frontend_Model_LocationContentBody();

            $bodyObj->setContent($bodyElement->nodeValue)
                    ->setLanguage($bodyElement->getAttribute('lg'))
                    ->setPriority($bodyElement->getAttribute('pri'));

            $obj->addBody($bodyObj);
            unset($bodyObj);
        }

        return $obj;
    }
}
