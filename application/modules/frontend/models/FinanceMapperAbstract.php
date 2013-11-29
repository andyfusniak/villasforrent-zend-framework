<?php
abstract class Frontend_Model_FinanceMapperAbstract extends Frontend_Model_MapperAbstract
{
    /**
     * Create an address object from an address row
     *
     * @param Common_Resource_Address_Row $addressRow the address db row
     * @return Frontend_Model_Address the address object structure
     */
    protected function _createAddressObjFromAddressRow(Common_Resource_Address_Row $addressRow)
    {
        $addressObj = new Frontend_Model_Address();
        $addressObj->setAddressId($addressRow->idAddress)
                   ->setName($addressRow->name)
                   ->setLine1($addressRow->line1)
                   ->setLine2($addressRow->line2)
                   ->setLine3($addressRow->line3)
                   ->setTownCity($addressRow->townCity)
                   ->setCounty($addressRow->county)
                   ->setPostcode($addressRow->postcode)
                   ->setCountry($addressRow->country)
                   ->setAdded($addressRow->added)
                   ->setUpdated($addressRow->updated);

        return $addressObj;
    }
}
