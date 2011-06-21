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


    public function gdImageToNewAspect($sourceGdImage, $targetX, $targetY)
    {
        $x = (float) imagesx($sourceGdImage);
        $y = (float) imagesy($sourceGdImage);
        
        $aspect        = $x / $y;
        $targetAspect  = $targetX / $targetY;
       
        //var_dump("source aspect = " . $aspect);
        //var_dump("target aspect = " . $targetAspect);
        if ($aspect == $targetAspect)  {
			$destGdImage = imagecreatetruecolor($targetX, $targetY);
			
			if (!imagecopyresampled($destGdImage, $sourceGdImage,
									0, 0,
									0, 0,
									$targetX, $targetY,
									imagesx($sourceGdImage), imagesy($sourceGdImage)))
				throw new Vfr_Exception("Failed to resize the image");
				
            return $destGdImage;
        }
        
        if ($aspect < $targetAspect) {
            // if the source aspect ratio is less than the target - e.g. 4:3 (or 1.3333) converting to 3:2 (or 1.5)
            // then we need to crop some off the top and bottom of the source image
            $newY = round($w * $targetAspect);
            
            var_dump($newY);
            die();
        } else {
            // if the source aspect ratio is greater than the target - e.g. 3:2 (or 1.5) converting to 4:3 (or 1.3333)
            // then we need to crop some off the sides the source image
            //var_dump("x = " . $x);
            //var_dump("y = " . $y);
            
            $newX = ($y * $targetAspect);
            //var_dump("newX", $newX);
            
            $needToLooseXLow  = ceil($x - $newX);
            $needToLooseXHigh = floor($x - $newX);
            //var_dump("need to loose", $needToLooseXLow, $needToLooseXHigh);
            //var_dump(189 * $targetAspect);
        
            $aspectLow  = ($x - $needToLooseXLow) / $y;
            $aspectHigh = ($x - $needToLooseXHigh) / $y;
            
            //var_dump("aspect",$aspectLow, $aspectHigh);
            
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
                $left = $right = floor($needToLooseX / 2);
            } else {
                // 1 pixel difference, so chop more of the right hand side
                $left  = floor($needToLooseX / 2);
                $right = $left + 1;
            }
           
            //var_dump($x);
            //var_dump($needToLooseX);
            //die();
            
            //var_dump("creating image ", (int) $x - $needToLooseX, (int) $y);
            //die();
            
            $destGdImage = imagecreatetruecolor($targetX, $targetY);
            
            if (!imagecopyresized($destGdImage, $sourceGdImage,
									0, 0,
									$left, 0,
									$targetX, $targetY,
									imagesx($sourceGdImage)-$left-$right, imagesy($sourceGdImage))) {
				
				throw new Vfr_Exception("Failed to resize the image");
			}
            imagedestroy($sourceGdImage);
            return $destGdImage;
         }
    }

}
