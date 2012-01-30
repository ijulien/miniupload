<?php 

// Generates salt for uploaded file
function generateSalt($max = 15) {
	$characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$i = 0;
	$salt = "";
	do {
		$salt .= $characterList{mt_rand(0,strlen($characterList))};
		$i++;
	} while ($i <= $max);
	return $salt;
}

// Opens connection to the database
function myconnect(){
	global $base_dbhost, $base_dbuser, $base_dbpassword, $base_dbtable;
	$con = mysql_connect($base_dbhost, $base_dbuser, $base_dbpassword);
	if (!$con) die('Could not connect: ' . mysql_error());
	mysql_select_db($base_dbtable, $con);
	return $con;
}

// Closes connection to the database
function myclose($con){
	if (!$con) die ('No MySQL connection found.');
	mysql_close($con);
}

// Returns path for the uploaded file
function return_file($salt){
	$file_dir  = dirname($_SERVER['SCRIPT_FILENAME'])."/tmp/";
	$file_path = $file_dir.$salt;
	$filename  = NULL;
	$con = myconnect();
	if (file_exists($file_path)) {
		$result = mysql_query("SELECT name FROM drop_file where salt='".$salt."'");
		$filename = mysql_fetch_row($result);
		$filename = $filename[0];
	}
	myclose($con);
	return $filename;
}

// Uploads a file and gets the salt in return
function do_upload($file){
	global $base_salt, $base_lifetime;
	// Specify folder for file upload
	$folder = dirname(__FILE__).'/tmp/'; 
	// Upload the file
	$salt_name=generateSalt($base_salt);
	move_uploaded_file($file["tmp_name"], $folder.$salt_name);
	// Get the file size
	$bytesize=filesize($file["tmp_name"]);
	// Insert the salt, realname and IP into the database
	$con = myconnect();
	mysql_query("INSERT INTO drop_file (salt, name, size, IP, expire_date) VALUES ('".$salt_name."', '".$file["name"]."', '".$bytesize."', '".$_SERVER['REMOTE_ADDR']."', DATE_ADD(NOW(), INTERVAL ".$base_lifetime." DAY))");
	myclose($con);
	return $salt_name;
}

// Deletes a file by a given salt, assuming it exists at least in the database
function do_delete($salt){
	// Build the folder string
	$file_path = getcwd().'/tmp/'.$salt; 
	// Delete the file...if it exists
	if (file_exists($file_path)) {
	unlink($path);
	}
	// Remove the database entry
	$con = myconnect();
	mysql_query("DELETE FROM drop_file WHERE salt = '".$salt."'");
	myclose($con);
}

// Downloads a file
function do_download($file){
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.$file_name);
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Content-Length: ' . filesize($file));
	ob_clean();
	flush();
	readfile($file);
	exit;
}


// Returns the total stored files size
function total_storage(){
	$con = myconnect();
	$sizecmd = mysql_query("SELECT SUM(size) AS total FROM drop_file"); 
	$size=mysql_fetch_assoc($sizecmd); 
	myclose($con);
	return format_bytes($size['total']);
}

// Formats bytes into a readable format
function format_bytes($a_bytes) {
	$decimals = 0;
    if ($a_bytes < 1024) {
        return $a_bytes .' B';
    } elseif ($a_bytes < 1048576) {
        return round($a_bytes / 1024, $decimals) .' KB';
    } elseif ($a_bytes < 1073741824) {
        return round($a_bytes / 1048576, $decimals) . ' MB';
    } elseif ($a_bytes < 1099511627776) {
        return round($a_bytes / 1073741824, $decimals + 2) . ' GB';
    } elseif ($a_bytes < 1125899906842624) {
        return round($a_bytes / 1099511627776, $decimals + 2) .' TB';
    } elseif ($a_bytes < 1152921504606846976) {
        return round($a_bytes / 1125899906842624, $decimals) .' PB';
    } elseif ($a_bytes < 1180591620717411303424) {
        return round($a_bytes / 1152921504606846976, $decimals) .' EB';
    } elseif ($a_bytes < 1208925819614629174706176) {
        return round($a_bytes / 1180591620717411303424, $decimals) .' ZB';
    } else {
        return round($a_bytes / 1208925819614629174706176, $decimals) .' YB';
    }
}

// Deletes both database entries and files who have exceeded the expiration time. Does nothing if otherwise.
function clean_expired() {
	$con = myconnect();
	$flushcmd = mysql_query("SELECT salt FROM drop_file WHERE expire_date < NOW() AND expire_date NOT LIKE '0000-00-00 00:00:00'");
	while ($flushlist=mysql_fetch_assoc($flushcmd)) {
		$saltstodelete[] = $flushlist['salt'];
	}
	myclose($con);
	if (isset($saltstodelete)) {
		foreach ($saltstodelete as $salt) {
			do_delete($salt);
		}
	}	
}

?>