<?php
class Vfr_Property_Converter
{
    private function __construct() {}


    public static function generateTopDir($idProperty, $blockInterval=50)
    {
        // e.g. 12345
        if ($idProperty < 10000)
            throw new Vfr_Exception("Property " . $idProperty . " out of range");

        // e.g. 12345 - 10000 = 2345
        $range = $idProperty - 10000;
        return 10000 + (floor($range/$blockInterval) * $blockInterval);
    }
}
