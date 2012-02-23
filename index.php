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
<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<!-- Le styles -->
    <link href="./bootstrap/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>
    <link href="./bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
<link type="text/css" href="style.css" rel="stylesheet" media="screen" />
<script src="upload.js"></script>
<script type="text/javascript">var switchTo5x=true;</script>
</head>
<body>
<div id="screen">
	<form id="form1" enctype="multipart/form-data" method="post" action="upload.php">
			<input type="hidden" name="MAX_FILE_SIZE" value="50000000" />
			<input type="file" name="fileToUpload" id="fileToUpload" onchange="fileSelected(); resetBar(); "/>  
			<input type="button" onclick="uploadFile();" id="Upload_button" value="Upload" />
 	</form>
	<div class="progress progress-info progress-striped">
		<div class="bar" style="width: 0%;">
			<p>&nbsp;</p>
		</div>
	</div>
  	<div id="info-upload">Select a file, upload & share!</div>
</div>
<div id="footer"><?php echo $app_name; ?> <?php echo $app_version; ?> - <?php echo total_storage(); ?> of storage used - <?php echo $expiration_text; ?></div>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="./bootstrap/js/jquery-1.7.1.min.js"></script>
    <script src="./bootstrap/js/bootstrap-transition.js"></script>
    <script src="./bootstrap/js/bootstrap-alert.js"></script>
    <script src="./bootstrap/js/bootstrap-modal.js"></script>
    <script src="./bootstrap/js/bootstrap-dropdown.js"></script>
    <script src="./bootstrap/js/bootstrap-scrollspy.js"></script>
    <script src="./bootstrap/js/bootstrap-tab.js"></script>
    <script src="./bootstrap/js/bootstrap-tooltip.js"></script>
    <script src="./bootstrap/js/bootstrap-popover.js"></script>
    <script src="./bootstrap/js/bootstrap-button.js"></script>
    <script src="./bootstrap/js/bootstrap-collapse.js"></script>
    <script src="./bootstrap/js/bootstrap-carousel.js"></script>
    <script src="./bootstrap/js/bootstrap-typeahead.js"></script>

</body>
</html>
