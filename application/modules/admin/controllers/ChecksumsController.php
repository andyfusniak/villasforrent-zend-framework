<?php
class Admin_ChecksumsController
    extends Zend_Controller_Action
{
    public function repairAction() {
        $propertyModel = new Common_Model_Property();
      
        // first repair the property content lines
        //$propertyModel->repairPropertyBatchChecksums();

        // get a list of properties
        // and update the main and update total checksum which is the sum of the
        // property content checksums
        $propertyRowset = $propertyModel->getAllProperties();

        $repairedList = array ();

        foreach ($propertyRowset as $propertyRow) {
            
            $checksumMaster = $propertyModel->checksumTotal(
                $propertyRow->idProperty,
                Common_Resource_PropertyContent::VERSION_MAIN,
                'EN'
            );
            
            $checksumUpdate = $propertyModel->checksumTotal(
                $propertyRow->idProperty,
                Common_Resource_PropertyContent::VERSION_UPDATE,
                'EN'
            );

            if ($checksumMaster != $propertyRow->checksumMaster) {
                $repairedList[] = 'Master checksum repaired for property id ' . $propertyRow->idProperty;
                //$propertyModel->updateMasterCheckSum($idProperty, $checksumMaster);
            }

            if ($checksumUpdate != $propertyRow->checksumUpdate) {
                 $repairedList[] = 'Update checksum repaired for property id ' . $propertyRow->idProperty;
                 //$propertyModel->updateMasterCheckSum($idProperty, $checksumUpdate);
            }

        }

        $this->view->assign(
            array (
                'repairedList' => $repairedList
            )
        );
    }

}