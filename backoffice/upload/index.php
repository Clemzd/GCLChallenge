<!DOCTYPE html>
<html>
<body>

<form action="index.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
	<input type="hidden" name="firstTime" value="true" id="firstTime">
    <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html>

<?php

include 'ServiceUtils.class.php';

if(isset($_POST["submit"])) {	
	
	$resize = new ServiceUtils();
	
	$exts = array('jpg', 'jpeg', 'png', 'bmp');
	
	$imgUploaded = $_FILES["fileToUpload"]["tmp_name"];
	$imgDest = "../images/".$_FILES["fileToUpload"]["name"];
	$ext = pathinfo($imgDest)['extension'];
	
	// Check if image file is a actual image
    if(in_array($ext, $exts) && filesize($imgUploaded) <= 8388608) {
       move_uploaded_file($imgUploaded, $imgDest);
	   
		$destkopImg = pathinfo($imgDest)['dirname']."/desktop/".pathinfo($imgDest)['basename'];
		$mobileImg = pathinfo($imgDest)['dirname']."/mobile/".pathinfo($imgDest)['basename'];
       
		// resize the image to match a width of 900px (desktop)
	   $resize->resizeWithProportion($imgDest, $destkopImg, 900, $ext);
	   // crop the image to match the height of 360px (desktop)
	   $resize->resizeCrop($destkopImg, $destkopImg, 360, $ext);
	   
	   // resize the image to match a width of 320px (mobile)
	  $resize->resizeWithProportion($imgDest, $mobileImg, 320, $ext);
	   // crop the image to match the height of 128px (mobile)
	  $resize->resizeCrop($mobileImg, $mobileImg, 128, $ext);
	  
	  // remove uploaded file
	  unlink($imgDest);
	   
    } else if (pathinfo($imgDest)['extension'] == 'prop') {
    	// case of propertie file, we had it in backoffice/images/
    	move_uploaded_file($imgUploaded, $imgDest);
    } else {
    	echo "<strong>File is not an image or is too big (File should be less than 8mo)</strong>";
    }
} else {
	echo "<strong>No file uploaded (File should be less than 8mo)</strong>";
}
?>	