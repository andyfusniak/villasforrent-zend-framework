<?php
class Vfr_DigestKey
{
    public static function generate($list)
    {
        $privateKey = "secret";

        $str = $privateKey;
        foreach ($list as $value) {
            $str .= $value;
        }

        if (APPLICATION_ENV == 'development')
            return $str;
        else
            return sha1($str);
    }
}
