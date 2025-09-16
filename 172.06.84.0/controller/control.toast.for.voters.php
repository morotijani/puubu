<?php 

	// DATABASE CONNECTION
	require_once("../../connection/conn.php");

	if (isset($_POST['toast'])) {
		if ($_POST['toast'] == 'toast') {
			$output = '';

			$query = "
				SELECT * FROM voterhasdone 
				INNER JOIN registrars
				ON registrars.voter_id = voterhasdone.vhd_id 
				INNER JOIN election 
				ON election.election_id = voterhasdone.election_id
				AND election.election_id = registrars.registrar_election
				WHERE voterhasdone_status = ?
				AND election.session = ?
			";
			$statement = $conn->prepare($query);
			$result = $statement->execute([0, 1]);
			$query_count = $statement->rowCount();
			$query_row = $statement->fetchAll();
			if ($query_count > 0) {
				foreach ($query_row as $row) {
					$output .= '
						<div class="toast" id="liveToast">
					      	<div class="toast-body bg-info text-white">
					        	<span class="text-warning fw-bold">'.ucwords($row["std_fname"] . ' ' .$row["std_lname"]).'</span>, just voted!
					        	<input type="hidden" id="vhd_id" value="'.$row["vhd_id"].'">
					      	</div>
					    </div>
					';
				}
			} else {
				$output = '';
			}
		echo $output;
		}
	}

?>