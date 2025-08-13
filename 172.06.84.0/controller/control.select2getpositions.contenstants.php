<?php 

	// DATABASE CONNECTION
	require_once("../../connection/conn.php");

	$cont_position = ((isset($_POST['cont_position']) && !empty($_POST['cont_position']))?sanitize($_POST['cont_position']):'');

	if (isset($_POST['action'])) {
		if ($_POST['action'] == 'sel_election') {
			$query = "SELECT * FROM positions WHERE election_id = '".$_POST["query"]."' GROUP BY position_id";
			$statement = $conn->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();
			$counter = $statement->rowCount();
			$output = '';
			$output = '<option value="">Select Position for Contestant</option>';
			if ($counter > 0) {
				foreach ($result as $row) {
					$output .= "<option value='".$row['position_id']."'".(($cont_position == $row['position_id'])?' selected': '').">".$row['position_name']."</option>";
				}
			}
			echo $output;
		}
	}






?>