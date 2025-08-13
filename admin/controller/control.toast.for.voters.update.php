<?php 


// DATABASE CONNECTION
require_once("../../connection/conn.php");

if (isset($_POST['voterhasdone_id'])) {
	$voterhasdone_id = sanitize((int)$_POST['voterhasdone_id']);

	$query = "
		UPDATE voterhasdone 
		SET voterhasdone_status = ?
		WHERE vhd_id = ?
	";
	$statement = $conn->prepare($query);
	$result = $statement->execute([1, $voterhasdone_id]);
	if (isset($result)) {
		echo 'status: 200';
	} else {
		echo 'status: 401';
	}
}


?>