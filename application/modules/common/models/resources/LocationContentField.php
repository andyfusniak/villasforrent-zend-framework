<?php
class Common_Resource_LocationContentField
    extends Vfr_Model_Resource_Db_Table_Abstract
{
    const IMAGE_URI     = 'image-uri';
    const IMAGE_CAPTION = 'image-caption';

    const HEADING = 'heading';
    const BODY    = 'body';

    public static $locationContentFieldNames = array(
        self::IMAGE_URI  => 'Image URI',
        self::IMAGE_CAPTION => 'Image Caption',
        self::HEADING => 'Main Heading',
        self::BODY    => 'Content Body'
    );
}
