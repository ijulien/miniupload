<?php

// START CONFIGURATION

// Base URL
$base_url="blabla.blabla.com";

// File upload configuration
$base_salt=10; // max 15
$base_lifetime=30; // in days, 0 for (almost) unlimited

// MySQL connection information
$base_dbhost="blabla.blabla.com";
$base_dbuser="username";
$base_dbpassword="password";
$base_dbtable="table";

// END CONFIGURATION

$app_name="Miniupload";
$app_version="v0.3";
if ($base_lifetime==0) { $base_lifetime=10000; $expiration_text="files never expire"; } else { $expiration_text="files expire after ".$base_lifetime." days"; }

?>