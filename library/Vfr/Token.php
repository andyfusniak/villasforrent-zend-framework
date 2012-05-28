<?php
class Vfr_Token
{
    public function generateUniqueToken()
    {
        return sha1(uniqid(mt_rand(), true));
    }
}
