<?php 

	// Delete An Uploaded File (i.e; Temporary Uploaded But Not Yet Posted) From Folder;;

	if (isset($_POST['tempuploded_file_id'])) {

		$tempuploded_img_id_filePath = $_POST['tempuploded_file_id'];

		unlink($tempuploded_img_id_filePath);
	}

?>