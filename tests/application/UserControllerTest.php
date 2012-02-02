<?php
class UserControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{
    public function setUp()
    {
        $this->bootstrap = new Zend_Application(
            'testing',
            APPLICATION_PATH . '/configs/application.ini'
        );

        parent::setUp();
    }

    public function testCanDoUnitTest()
    {
        $this->assertTrue(true);
    }

    public function testAcl()
    {
        $aclModel = new Controlpanel_Model_Acl_Controlpanel();


    }
}
