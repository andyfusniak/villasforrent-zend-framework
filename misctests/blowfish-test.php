<?php
require_once 'BlowfishHasher.php';


# create a new hasher instance with
# an iteration count of 8
$hasher = new Vfr_BlowfishHasher(8);

// method function hash converts the plain text
// password into a hash using OpenBSD-style Blowfish-based bcrypt
// throws a Vfr_Exception_BlowfishUnsupported is BLOW_FISH isn't available
try {
    $hash = $hasher->hash('somepassword');
} catch (Vfr_Exception_BlowfishUnsupported $e) {
    die("No blowfish, no dinner");
}

// use the checkPassword method function to valid passwords
if ($hasher->checkPassword('somepassword', $hash)) {
    var_dump("MATCH");
}





