<?php 

require_once("../../connection/conn.php");

if (isset($_POST['startElec'])) {
	$startElecID = $_POST['startElec'];

	$query = "SELECT * FROM election WHERE eid = ? AND session = ?";
	$statement = $conn->prepare($query);
	$statement->execute([$startElecID, 0]);
	$result_count = $statement->rowCount();

	if ($result_count > 0) {
		echo "Am sorry You can't Start Two Elections At The Same Time.";
	} else {
		$query = "UPDATE election SET session = '1' WHERE eid = '".$startElecID."'";
		$statement = $conn->prepare($query);
		$_result = $statement->execute();
		if (isset($_result)) {
			echo "Election Has Been Started Successfully";
		}
	}
}



?>