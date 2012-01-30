<?php 

$_GET['no'] = 1;

include 'config.php';
include 'gear.php';
clean_expired();

if ( strstr($_SERVER['HTTP_USER_AGENT'], "curl") ) {
	if ($_FILES){
		$salt = do_upload($_FILES['file']);
		header('Content-type: text/plain');
		echo "http://".$base_url."/".$salt."\n";  
	} else {
		header('Content-type: text/plain');
		echo "Error : No valid POST data found.\n";
	}
	exit();
}

// Split the path by '/'  
$params    = explode("/", $_SERVER['REQUEST_URI']);

// If the file exists, return it.
if ($params[1] != "" ) {
	$file_name = return_file($params[1]);
	$file =dirname(__FILE__).'/tmp/'.$params[1];
	if (file_exists($file)){
		do_download($file);
	} else {
		header("HTTP/1.0 404 Not Found");
		exit;
	}
}

?>
<html>
<head><title><?php echo $app_name; ?></title>
<meta name="viewport" content="width=400, initial-scale=1">
<link type="text/css" href="style.css" rel="stylesheet" media="screen" />
<script src="upload.js"></script>
</head>
<body>
<form id="form1" enctype="multipart/form-data" method="post" action="upload.php">
  <div class="row">
    <label for="fileToUpload">Choose a file to upload (Max Size : 50Mb)</label><br />
    <input type="hidden" name="MAX_FILE_SIZE" value="50000000" />
    <input type="file" name="fileToUpload" id="fileToUpload" onchange="fileSelected();"/>  
  </div>
  <div class="row">
  <input type="button" onclick="uploadFile()" value="Share" />
  <div id="uploadResponse"></div>
  </div>
  <!-- <div id="fileInfo">
    <div id="fileName"></div>
    <div id="fileSize"></div>
    <div id="fileType"></div>
  </div> -->
  <div class="row"></div>
  <div id="progressIndicator">
    <div id="progressBar"><br />
    </div>
    <div id="progressNumber">&nbsp;</div>
    <div class="clear"></div>
    <div align="center">
      <div id="info-upload">&nbsp;</div>
      <!-- <div id="transferBytesInfo" class="floatRight" style="text-align: right;">&nbsp;</div> -->
      <div class="clear"></div>
    </div>
  </div>  
</form>
<div id="footer"><?php echo $app_name; ?> <?php echo $app_version; ?> - <?php echo total_storage(); ?> of storage used</div>

</body>
</html>
