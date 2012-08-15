<?php
class Common_Service_WebProductsMap
{
    /**
     * @return array hash map of web product objects with the key being the web-product id (primary key)
     */
    public function getAllWebProductsMap()
    {
        $webProductResource = new Common_Resource_WebProduct();

        $hashmap = array();
        $webProductRowset = $webProductResource->getAllWebProducts();
        foreach ($webProductRowset as $webProductRow) {
            $idWebProduct = $webProductRow->idWebProduct;

            // create the new web-products object
            $webProductObj =  new Frontend_Model_WebProduct();
            $webProductObj->setWebProductId($webProductRow->idWebProduct)
                          ->setProductCode($webProductRow->productCode)
                          ->setName($webProductRow->name)
                          ->setUnitPrice($webProductRow->unitPrice)
                          ->setRepeats($webProductRow->repeats)
                          ->setDescription($webProductRow->description)
                          ->setAdded($webProductRow->added)
                          ->setUpdated($webProductRow->updated);

            // index it in the hash
            $hashmap[$idWebProduct] = $webProductObj;
        }

        return $hashmap;
    }
}
