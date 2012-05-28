<?php
class Vfr_Image_Processor
{
    protected static $_instance;

    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Enforce singleton; disallow constructor
     *
     * @return void
     */
    private function __construct() {}

    /**
     * Enforce singleton; disallow cloning
     *
     * @return void
     */
    private function __clone() {}


    public function gdImageToNewAspect($originalGdImage, $targetX, $targetY)
    {
        $originalX = (float) imagesx($originalGdImage);
        $originalY = (float) imagesy($originalGdImage);

        $originalAspect  = $originalX / $originalY;
        $targetAspect    = $targetX / $targetY;

        //var_dump("source aspect = " . $originalAspect);
        //var_dump("target aspect = " . $targetAspect);

        //die();

        if ($originalAspect == $targetAspect)  {
            //die('AR match');
            $destGdImage = imagecreatetruecolor($targetX, $targetY);

            if (!imagecopyresampled(
                    $destGdImage, $originalGdImage,
                    0, 0, // dst_x, dst_y
                    0, 0, // src_x, src_y
                    $targetX, $targetY, // dst_w, dst_h
                    imagesx($originalGdImage), imagesy($originalGdImage) // src_w, src_h
                ))
                    throw new Vfr_Exception("Failed to resize the image");

            return $destGdImage;
        }

        if ($originalAspect < $targetAspect) {
            // if the original AR is less than the target AR
            // e.g. 4:3 (AR = 1.3333) converting to 3:2 (AR = 1.5)
            // then we need to crop some off the top and/or bottom
            // of the original image before rescaling takes place

            $newY = $originalX / $targetAspect;

            $needToLooseYLow  = ceil($originalY - $newY);
            $needToLooseYHigh = floor($originalY - $newY);

            //var_dump("NeedToLooseYLow=$needToLooseYLow, NeedToLooseYHigh=$needToLooseYHigh");

            $aspectLow  = $originalX / ($originalY - $needToLooseYLow);
            $aspectHigh = $originalX / ($originalY - $needToLooseYHigh);

            //var_dump("aspectLow=$aspectLow, aspectHigh=$aspectHigh");

            //var_dump("NewY=$newY, originalY=$originalY, targetAspect=$targetAspect");

            $diffLow  = abs($targetAspect - $aspectLow);
            $diffHigh = abs($targetAspect - $aspectHigh);

            //var_dump("diffLow=$diffLow, diffHigh=$diffHigh");

            //die('example 1');
            if ($diffLow < $diffHigh) {
                $needToLooseY = (int) $needToLooseYLow;
            } else {
                $needToLooseY = (int) $needToLooseYHigh;
            }


            if (($needToLooseY % 2) == 0) {
                // divides equally, so chop half of each side
                $top = $bottom = $needToLooseY / 2;
            } else {
                // 1 pixel difference, so chop more of the right hand side
                $top  = floor($needToLooseY / 2);
                $bottom = $top + 1;
            }

            //var_dump("Need to loose Y=" . $needToLooseY);
            //var_dump("top=$top, bottom=$bottom");
            //die();

            $destGdImage = imagecreatetruecolor($targetX, $targetY);
            //var_dump("Created image X=$targetX, Y=$targetY");
            //die();

            if (!imagecopyresampled(
                    $destGdImage, $originalGdImage,
                    0, 0, // dst_x, dst_y
                    0, $top, // src_x, src_y
                    $targetX, $targetY, // dst_w, dst_h
                    imagesx($originalGdImage), imagesy($originalGdImage)-$top-$bottom // src_w, src_h
                )) {

                throw new Vfr_Exception("Failed to resize the image");
            }

            return $destGdImage;
        } else {
            // if the original AR is greater than the target AR
            // e.g. 3:2 (AR=1.5) converting to 4:3 (AR=1.3333)
            // then we need to crop some off the sides the source image
            $newX = ($originalY * $targetAspect);

            //var_dump("newX=", $newX);

            $needToLooseXLow  = ceil($originalX - $newX);
            $needToLooseXHigh = floor($originalX - $newX);
            //var_dump("NeedToLooseXLow=$needToLooseXLow, NeedToLooseXHigh=$needToLooseXHigh");

            $aspectLow  = ($originalX - $needToLooseXLow) / $originalY;
            $aspectHigh = ($originalX - $needToLooseXHigh) / $originalY;

            //var_dump("aspectLow=$aspectLow, aspectHigh=$aspectHigh");


            $diffLow  = abs($targetAspect - $aspectLow);
            $diffHigh = abs($targetAspect - $aspectHigh);

            if ($diffLow < $diffHigh) {
                $needToLooseX = (int) $needToLooseXLow;
            } else {
                $needToLooseX = (int) $needToLooseXHigh;
            }

            //var_dump("diffs", $diffLow, $diffHigh, $needToLooseX);


            if (($needToLooseX % 2) == 0) {
                // divides equally, so chop half of each side
                $left = $right = $needToLooseX / 2;
            } else {
                // 1 pixel difference, so chop more of the right hand side
                $left  = floor($needToLooseX / 2);
                $right = $left + 1;
            }

            //var_dump("left=$left, right=$right");
            //die();
            //var_dump($x);
            //var_dump($needToLooseX);
            //die();

            //var_dump("creating image ", (int) $x - $needToLooseX, (int) $y);
            //die();

            $destGdImage = imagecreatetruecolor($targetX, $targetY);
            if (!imagecopyresampled(
                    $destGdImage, $originalGdImage,
                    0, 0,
                    $left, 0,
                    $targetX, $targetY,
                    imagesx($originalGdImage)-$left-$right, imagesy($originalGdImage)
                )) {
                throw new Vfr_Exception("Failed to resize the image");
            }

            return $destGdImage;
         }
    }

}
