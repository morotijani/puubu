<?php 

require_once("../../connection/conn.php");

if (isset($_POST['election-session'])) {
	$startElecID = $_POST['election-session'];

	$query = "SELECT * FROM election WHERE eid = ?";
	$statement = $conn->prepare($query);
	$statement->execute([$startElecID]);
	$result_count = $statement->rowCount();

	if ($result_count > 0) {
		// $queryStop = "UPDATE election SET session = '2' WHERE eid = '".$startElecID."'";
		// $statement = $conn->prepare($queryStop);
		// $result = $statement->execute();
		// if (isset($result)) {
		// 	echo "Election Has Been Stopped, <span class='text-warning'>To get End of session Results</span>";
		// }
	//} else {
		$queryStart = "UPDATE election SET session = ?, stop_timer = ? WHERE eid = ?";
		$statement = $conn->prepare($queryStart);
		$_result = $statement->execute([1, $_POST['ctimer'], $startElecID]);
		if (isset($_result)) {
			echo "Election Has Been Started Successfully";
		}
	}
}



?>