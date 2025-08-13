<?php 

require_once("../../connection/conn.php");

if (isset($_POST['stopElec'])) {
	$stopElecID = $_POST['stopElec'];

	$query = "SELECT * FROM election WHERE session = '1'";
	$statement = $conn->prepare($query);
	$statement->execute();
	$result_count = $statement->rowCount();

	if ($result_count < 0) {
		echo "Am sorry There are no Elections To Stop.";
	} else {
		$query = "UPDATE election SET session = '0' WHERE eid = '".$stopElecID."'";
		$statement = $conn->prepare($query);
		$_result = $statement->execute();
		if (isset($_result)) {
			echo "Election Has Been Stopped Successfully";
		}
	}
}



?>