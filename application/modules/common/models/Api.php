<?php
class Common_Model_Api extends Vfr_Model_Abstract
{
    public function hasAuthorisation($apiKey)
    {
        return $this->getResource('ApiAccount')->hasAuthorisation($apiKey);
    }
}
