<?php

include 'config.php';
include 'gear.php';

if ( isset($_GET['no']) ) return;

if(! count($_FILES)>0) { 
    header('Location: http://$base_url');
}

if ($_FILES['fileToUpload']['error']) {
    switch ($_FILES['fileToUpload']['error']){
        case 1: // UPLOAD_ERR_INI_SIZE
            $error="The file is bigger than this PHP installation allows.";
            break;
        case 2: // UPLOAD_ERR_FORM_SIZE
            $error="The file is bigger than this form allows.";
            break;
        case 3: // UPLOAD_ERR_PARTIAL
            $error="Only part of the file was uploaded.";
            break;
        case 4: // UPLOAD_ERR_NO_FILE
            $error="No file was uploaded.";
            break;
    }
}
if ( isset($error) ){
	echo "<span class=\"notice\">Error :</span> $error";
}else{
	$salt = do_upload($_FILES['fileToUpload']);
	echo "<a href=\"http://".$base_url."/$salt\">".$_FILES['fileToUpload']['name']."</a>";
	$subject = $_FILES['fileToUpload']['name'];
	$body = urlencode("http://".$base_url."/".$salt);
	// echo "<a href=\"mailto:custom@custom.com&subject=".$subject."&body=".$body."\">"."Mail this"."</a>";  
}

exit();

?>
