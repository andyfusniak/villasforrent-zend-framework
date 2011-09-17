<?php
class Vfr_BlowfishHasher
{
    private $_iterationCountLog2;
    private $_randomState;
    
    public function __construct($iterationCountLog2)
    {
        if ($iterationCountLog2 < 4 || $iterationCountLog2 > 31)
            $iterationCountLog2 = 8;
            
        $this->_iterationCountLog2 = $iterationCountLog2;
        
        $this->_randomState = microtime();
        if (function_exists('getmypid'))
            $this->_randomState .= getmypid();
    }
    
    private function getRandomBytes($count)
    {
        $output = '';
        if (is_readable('/dev/urandom') && ($fh = @fopen('/dev/urandom', 'rb'))) {
            $output = fread($fh, $count);
            fclose($fh);
        }

        if (strlen($output) < $count) {
            $output = '';
            for ($i = 0; $i < $count; $i += 16) {
                $this->_randomState = md5(microtime() . $this->_randomState);
                $output .= pack('H*', md5($this->_randomState));
            }
            $output = substr($output, 0, $count);
        }
        
        return $output;
    }
    
    private function generateBlowfishSalt($input)
    {
        $itoa64 = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        $output = '$2a$';
        $output .= chr(ord('0') + $this->_iterationCountLog2 / 10);
        $output .= chr(ord('0') + $this->_iterationCountLog2 % 10);
        $output .= '$';

        $i = 0;
        do {
            $c1 = ord($input[$i++]);
            $output .= $itoa64[$c1 >> 2];
            $c1 = ($c1 & 0x03) << 4;
            if ($i >= 16) {
                $output .= $itoa64[$c1];
                break;
            }

            $c2 = ord($input[$i++]);
            $c1 |= $c2 >> 4;
            $output .= $itoa64[$c1];
            $c1 = ($c2 & 0x0f) << 2;

            $c2 = ord($input[$i++]);
            $c1 |= $c2 >> 6;
            $output .= $itoa64[$c1];
            $output .= $itoa64[$c2 & 0x3f];
        } while (1);

        return $output;
    }
    
    public function hash($passwd)
    {
        if (CRYPT_BLOWFISH != 1) {
            require_once 'Vfr/Exception/BlowfishUnsupported.php';
            throw new Vfr_Exception_BlowfishUnsupported();
        }
        $random = $this->getRandomBytes(16);    
        
        $hash = crypt($passwd, $this->generateBlowfishSalt($random));
        
        if (strlen($hash) != 60) {
            require_once 'Vfr/Exception/BlowfishInvalidHash.php';
            throw Vfr_Exception_BlowfishInvalidHash();
        }
        return $hash;
    }
    
    public function checkPassword($passwd, $hash)
    {
        if (strlen($hash) != 60) {
            require_once 'Vfr/Exception/BlowfishInvalidHash.php';
            throw new Vfr_Exception_BlowfishInvalidHash();
        }

        $checkHash = crypt($passwd, $hash);
       
        return ($checkHash == $hash);
    }
}
