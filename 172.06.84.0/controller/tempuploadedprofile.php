<?php 

	// Upload Post Image And Save To Draft 

	// Include External Files;
	require_once ('../../connection/conn.php');

	if ($_FILES["cont_profile"]["name"]  != '') {

		$test = explode(".", $_FILES["cont_profile"]["name"]);

		$extention = end($test);

		$name = rand(100, 999) . '.' . $extention;

		$location = BASEURL.'172.06.84.0/media/tempuploadedprofile/'.$name;

		move_uploaded_file($_FILES["cont_profile"]["tmp_name"], $location);

		echo '
				<div id="removeTempuploadedFile">
					<img src="./media/tempuploadedprofile/'.$name.'" id="removeImage" class="img-fluid">
					<input type="hidden" name="uploadedPassport" id="uploadedPassport" value="'.$location.'">
					<br>
					<a href="javascript:void(0)" id="'.$location.'" class="removeImg text-warning font-weight-bolder">remove</a>
				</div>';
	}

?>

<style type="text/css">
	div#removeTempuploadedFile > img#removeImage {
	    height: 200px !important;
	    object-fit: contain;
	    border-radius: 1rem;
	}
</style>