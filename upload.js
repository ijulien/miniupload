var bytesUploaded       = 0;
var bytesTotal          = 0;
var previousBytesLoaded = 0;
var intervalTimer       = 0;

function fileSelected() {
    var file = document.getElementById('fileToUpload').files[0];
    var fileSize = 0;
    if (file.size > 1024 * 1024)
        fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
    else
        fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';
    // document.getElementById('fileInfo').style.display = 'block';
    // document.getElementById('fileName').innerHTML = 'Name: ' + file.name;
    // document.getElementById('fileSize').innerHTML = 'Size: ' + fileSize;
    // document.getElementById('fileType').innerHTML = 'Type: ' + file.type;
}

function resetBar() {
    $(".bar").css("width", "0%");
    $(".bar p").text("");
}

function uploadFile() {
    if (document.getElementById('fileToUpload').files[0] == undefined ) {
        alert("Please select a file first...");
        return
    }
    var filename = document.getElementById('fileToUpload').files[0].name;
    $("input[type=button]").attr("disabled", "disabled");
    $("input[type=button]").attr('value','Uploading...');
    previousBytesLoaded = 0;

    /* If you want to upload only a file along with arbitary data that
     is not in the form, use this */
    var fd = new FormData();
    fd.append("fileToUpload", document.getElementById('fileToUpload').files[0]);

    /* If you want to simply post the entire form, use this */
    // var fd = document.getElementById('form1').getFormData();

    var xhr = new XMLHttpRequest();        
    xhr.upload.addEventListener("progress", uploadProgress, false);
    xhr.addEventListener("load", uploadComplete, false);
    xhr.addEventListener("error", uploadFailed, false);
    xhr.addEventListener("abort", uploadCanceled, false);
    xhr.open("POST", "upload.php");
    xhr.send(fd);

    intervalTimer = setInterval(updateTransferSpeed, 500);
}

function updateTransferSpeed() {
    var currentBytes = bytesUploaded;
    var bytesDiff = currentBytes - previousBytesLoaded;
    var speed = "";
    var bytesRemaining = bytesTotal - previousBytesLoaded;
    var secondsRemaining = bytesRemaining / bytesDiff;

    if (bytesDiff == 0) return;
    previousBytesLoaded = currentBytes;
    bytesDiff = bytesDiff * 2;

    if (bytesDiff > 1024 * 1024)
        speed = (Math.round(bytesDiff * 100/(1024*1024))/100).toString() + 'MBps';
    else if (bytesDiff > 1024)
        speed =  (Math.round(bytesDiff * 100/1024)/100).toString() + 'KBps';
    else
        speed = bytesDiff.toString() + 'Bps';
    document.getElementById('info-upload').innerHTML = speed + ' - ' + secondsToString(secondsRemaining) + ' remaining';        
}

function secondsToString(seconds) {        
	var h = Math.floor(seconds / 3600);
	var m = Math.floor(seconds % 3600 / 60);
	var s = Math.floor(seconds % 3600 % 60);
	return ((h > 0 ? h + ":" : "") + (m > 0 ? (h > 0 && m < 10 ? "0" : "") + m + ":" : "0:") + (s < 10 ? "0" : "") + s);
}

function uploadProgress(evt) {
    if (evt.lengthComputable) {
        bytesUploaded = evt.loaded;
        bytesTotal = evt.total;
        var percentComplete = Math.round(evt.loaded * 100 / evt.total);
        var bytesTransfered = '';
        if (bytesUploaded > 1024*1024)
            bytesTransfered = (Math.round(bytesUploaded * 100/(1024*1024))/100).toString() + 'MB';
        else if (bytesUploaded > 1024)
            bytesTransfered = (Math.round(bytesUploaded * 100/1024)/100).toString() + 'KB';
    else
        bytesTransfered = (Math.round(bytesUploaded * 100)/100).toString() + 'Bytes';

        $(".bar").css("width", percentComplete.toString() + '%');
	 $(".bar p").text(percentComplete.toString() + '%');
        if (percentComplete == 100) {
            // do something
        }
    }else{
        	// do something else
    }  
}

function uploadComplete(evt) {
	clearInterval(intervalTimer);
	$(".bar").css("width", "100%");
	$(".bar p").text("100%");
	$("input[type=button]").attr('value','Upload');
	$("input[type=button]").removeAttr("disabled");
	$("#form1")[0].reset();
	$("#info-upload").text("Upload Completed");
	$('<div id="uploadResponse"><div class="alert alert-info"><a class="close" data-dismiss="alert" href="#">&times;</a><i class="icon-file"></i>' + evt.target.responseText + '</div></div>').insertAfter('#info-upload');
}  

function uploadFailed(evt) {
	clearInterval(intervalTimer);
	$('<div id="uploadResponse"><div class="alert"><a class="close" data-dismiss="alert" href="#">&times;</a><i class="icon-file"></i>' + filename + ' has not been uploaded.</div></div>').insertAfter('#info-upload');
	$("input[type=button]").attr('value','Upload');
	$("input[type=button]").removeAttr("disabled");
	$("#form1")[0].reset();
	$("#info-upload").text("Last upload failed");
	// alert("An error occurred while uploading the file.");  
}  

function uploadCanceled(evt) {
	clearInterval(intervalTimer);
	$("input[type=button]").attr('value','Upload');
	$("input[type=button]").removeAttr("disabled");
	$("#form1")[0].reset();
	$("#info-upload").text("Last upload canceled");
	alert("The upload has been canceled by the user or the browser dropped the connection.");  
}  