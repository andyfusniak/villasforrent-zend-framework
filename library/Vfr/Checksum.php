<?php
class Vfr_Checksum
{
    public static function cs($data)
    {
        return sha1($data);
    }
}
