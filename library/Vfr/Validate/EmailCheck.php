<?php
class Vfr_Validate_EmailCheck extends Zend_Validate_EmailAddress
{
    const version = '1.0.0';

    public function getMessages()
    {
        return (array) "Your email address is not valid";

        $messages = array_values($this->_messages);
        return (array)$messages[0]; //Return only the first error for each time
    }
}
