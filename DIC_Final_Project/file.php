<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$ok = "";
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(empty(basename($_FILES["fileToUpload"]["name"]))) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    $ok .= "You need to send a file with your resume. Try again. "; 
} else {
	// Check if file already exists
	if (file_exists($target_file)) {
		$ok .= " Sorry, file already exists.";
		$uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) {
		$ok .= " Sorry, your file is too large.";
		$uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "doc" && $imageFileType != "docx" && $imageFileType != "pdf" ) {
		$ok .= " Sorry, only Word and PDF files are allowed.";
		$uploadOk = 0;
		
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		$ok .= " Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			//echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			chmod($target_file,0755);
		} else {
			$ok .= " Sorry, there was an error uploading your file.";
		}
	}
  }
?>
