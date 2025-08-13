<?php 

	// DATABASE CONNECTION
	require_once("../../connection/conn.php");

	if (isset($_POST['election_id'])) {
		$election_id = sanitize((int)$_POST['election_id']);

		$election_manual_stop_time = date("Y-m-d H:i:s");

		$query = "
			UPDATE election 
			SET election.session = ?, election.election_manual_stop_time = ?
			WHERE election.eid = ?
		";
		$statement = $conn->prepare($query);
		$result = $statement->execute([2, $election_manual_stop_time, $election_id]);
		if (isset($result)) {
			$_SESSION['flash_success'] = 'Election Stopped Manually';
			echo 'end election';
		}
	}



 ?>