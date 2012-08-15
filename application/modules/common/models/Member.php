<?php
class Common_Model_Member extends Vfr_Model_Abstract
{
    const version = '1.0.0';
    const SESSION_NS_ADMIN_MEMBER = 'AdminMemberNS';

    public function addMember($emailAddress, $passwd, $firstname, $lastname = null)
    {
        // start a transaction
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->beginTransaction();

        try {
            $idMember = $this->getResource('Member')->addMember(
                $emailAddress,
                $passwd,
                $firstname
            );

            // create a confirmation token
            // first, generate a new random token
            $tokenObj = new Vfr_Token();
            $token = $tokenObj->generateUniqueToken();
            $this->getResource('Token')->addEmailConfirmation(
                $idMember,
                $token,
                Common_Resource_Token::TYPE_MEMBER
            );

            // commit the transaction
            $db->commit();
        } catch (Exception $e) {
            // rollback as things didn't go to plan
            $db->rollBack();

            throw $e;
        }
    }

    public function getAllPaginator($page, $interval, $order, $direction)
    {
        $session = new Zend_Session_Namespace(self::SESSION_NS_ADMIN_MEMBER);

        // defaults
        if (null !== $page)
            $session->page = $page;

        if (null !== $interval)
            $session->interval = $interval;

        if (null !== $order)
            $session->order = $order;

        if (null !== $direction)
            $session->direction = $direction;

        //foreach ($session as $name => $value) {
        //    var_dump($name . ' = ' . $value);
        //}

        return $this->getResource('Member')->getAllPaginator(
            isset($session->page)      ? $session->page : 1,
            isset($session->interval)  ? $session->interval : 10,
            isset($session->order)     ? $session->order : 'idMember',
            isset($session->direction) ? $session->direction : 'ASC'
        );
    }

    public function getMemberByMemberId($idMember)
    {
        return $this->getResource('Member')->getMemberByMemberId($idMember);
    }

    public function getMemberByEmail($emailAddress)
    {
        return $this->getResource('Member')->getMemberByEmail($emailAddress);
    }

    public function getMemberEmailConfirmationDetailsByToken($token)
    {
        return $this->getResource('Token')
                    ->getMemberEmailConfirmationDetailsByToken($token);
    }

    /**
     * Returns the member row or null if the login details are correct
     * @param string $emailAddress the email address for this member
     * @param string $paswd the member's password
     * @return Common_Resource_Member|null the member row
     */
    public function login($emailAddress, $passwd)
    {
        $memberResource = $this->getResource('Member');

        try {
            $memberRow = $memberResource->login($emailAddress, $passwd);

            if ($memberRow)
                $memberResource->updateLastLogin($memberRow->idMember);
        } catch (Exception $e) {
            throw $e;
        }

        return $memberRow;
    }
}
