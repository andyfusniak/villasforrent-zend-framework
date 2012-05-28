<?php
class Admin_LocationTreeCheckController extends Zend_Controller_Action
{
    public function checkAction()
    {
        $locationService = new Common_Service_Location();

        list($locationRowset, $leftLookup, $rightLookup) = $locationService->getAllLocationsAssocArrays();


        // note the max rt value is fixed
        // this should really be read from the DB
        //

        for($i=1; $i<= 372; $i++) {
            if (!isset($leftLookup[$i])) {
                if (!isset($rightLookup[$i])) {
                    var_dump('Node idLocation=' . $i . ' is missing from the nested set');
                }
            }
        }

        $this->view->assign(
            array (
                'locationRowset' => $locationRowset
            )
        );
    }

    public function testMoveAction()
    {
        $locationModel = new Common_Model_Location();

        $france = 66;
        $turkey = 145;
        $greece = 170;

        $idLocationParent = $locationModel->getParentIdByChildId(321);

        $locationModel->moveLocation(
            327,
            3,
            Common_Resource_Location::NODE_BEFORE
        );

        $this->view->assign(
            array (
                'idLocationParent'  => $idLocationParent
            )
        );
    }

    public function testAddAction()
    {
        $locationModel = new Common_Model_Location();

        //$parent = 82;

        //$locationRowA = $locationModel->addLocationToParentFirst(
        //    $parent,
        //    'Antibes'
        //);

        //$locationRowB = $locationModel->addLocationToParentLast(
        //    $parent,
        //    'Another'
        //);
    }

    public function testDeleteAction()
    {
        $locationModel = new Common_Model_Location();

        $idLocation = 327; // greece/crete/gavalochor

        $locationModel->deleteLocation($idLocation);
    }
}
