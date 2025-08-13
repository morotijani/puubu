<?php 

	// Make Date Redable
	function pretty_date($date){
		return date("M d, Y h:i A", strtotime($date));
	}
	

















	// Sessions For login
	function cAdminLoggedInID($cadmin_id) {
		$_SESSION['crAdmin'] = $cadmin_id;
		global $conn;
		$data = array(
			':last_login' => date("Y-m-d H:i:s"),
			':c_aid' => (int)$cadmin_id
		);
		$query = "UPDATE puubu_admin SET last_login = :last_login WHERE c_aid = :c_aid";
		$statement = $conn->prepare($query);
		$result = $statement->execute($data);
		if (isset($result)) {
			$_SESSION['flash_success'] = '<div class="text-center" id="temporary">You are now logged in!</div>';
			header('Location: index');
		}
	}

	function cadminIsLoggedIn(){
		if (isset($_SESSION['crAdmin']) && $_SESSION['crAdmin'] > 0) {
			return true;
		}
		return false;
	}

	// Redirect If not Logged in
	function cadminLoginErrorRedirect($url = 'signin') {
		$_SESSION['flash_error'] = '<div class="text-center" id="temporary" style="margin-top: 60px;">Oops... you must be logged in to access that page.</div>';
		header('Location: '.$url);
	}

	function admin_permission_error_redirect($url = 'signin'){
		$_SESSION['error_flash'] = '<div class="text-center" style="margin-top: 60px;">You do not have permission to that page.</div>';
		header('Location: '.$url);
	}

	// GET ADMIN PROFILE DETAILS
	function get_admin_profile() {
		global $conn;
		global $row;
		$output = '';

		$query = "
			SELECT * FROM puubu_admin 
			WHERE trash = :trash 
			LIMIT 1
		";
		$statement = $conn->prepare($query);
		$statement->execute([':trash' => 0]);
		$result = $statement->fetchAll();

		foreach ($result as $admin_row) {
			if ($admin_row['c_aid'] == $row['c_aid']) {
				$output = '
					<h6>First Name</h6>
				    <p class="lead text-info">'.ucwords($admin_row["cfname"]).'</p>
				    <br>
					<h6>Last Name</h6>
				    <p class="lead text-info">'.ucwords($admin_row["clname"]).'</p>
				    <br>
				    <h6>Email</h6>
				    <p class="lead text-info">'.$admin_row["cemail"].'</p>
				    <br>
				    <h6>Joined Date</h6>
				    <p class="lead text-info">'.pretty_date($admin_row["joined_date"]).'</p>
				    <br>
				    <h6>Last Login</h6>
				    <p class="lead text-info">'.pretty_date($admin_row["last_login"]).'</p>
				';
			}
		}
		return $output;
	}



















    global $conn;
	$query = "SELECT * FROM election";
  	$statement = $conn->prepare($query);
  	$statement->execute();
  	$all_elections_result = $statement->fetchAll();
  	$listall_election = $statement->rowCount();
  	foreach ($all_elections_result as $main_row) {}

  	// 1 = Started, 2 = Ended, 0 = ''
  	$queryS = "SELECT * FROM election WHERE session = '1' OR session = '2' LIMIT 1";
  	$statement = $conn->prepare($queryS);
  	$statement->execute();
  	$stated_election_result = $statement->fetchAll();
  	$started_election = $statement->rowCount();
  	foreach ($stated_election_result as $sub_row) {}

	// GET THE TOTAL NUMBER OF VOTERS
	function count_voters() {
		global $conn;
		$query = "SELECT * FROM `registrars` INNER JOIN election ON election.eid = registrars.election_type";
		$statement = $conn->prepare($query);
		$statement->execute();
		return $statement->rowCount();
	}

	// GET THE TOTAL NUMBER OF CONTESTANTS UNDER STARTED ELECTION
	function count_contestants() {
		global $conn;
		$query = "SELECT * FROM cont_details INNER JOIN election WHERE election.eid = cont_details.election_name AND election.session = '1'";
		$statement = $conn->prepare($query);
		$statement->execute();
		return $statement->rowCount();
	}

	// GET THE TOTAL NUMBER OF POSITIONS UNDER STARTED ELECTION
	function count_positions() {
		global $conn;
		$query = "SELECT * FROM positions INNER JOIN election WHERE election.eid = positions.election_id AND election.session = '1'";
		$statement = $conn->prepare($query);
		$statement->execute();
		return $statement->rowCount();
	}

	// GET THE NUMBER OF APPLIED APPLICANTS (NOT-VERIFIED)
	function count_votes() {
		global $conn;
		$query = "SELECT COUNT(*) as all_voterhasdone FROM voterhasdone";
		$statement = $conn->prepare($query);
		$statement->execute();
		foreach ($statement->fetchAll() as $row) {
			return $row['all_voterhasdone'];
		}
	}


//////////////////////////////

	// GET THE TOTAL NUMBER OF VOTERS
	function count_voters_on_runing_election($election_id) {
		global $conn;
		$query = "SELECT * FROM `registrars` INNER JOIN election ON election.eid = ? AND registrars.election_type = ? WHERE election.session = ? OR election.session = ?";
		$statement = $conn->prepare($query);
		$statement->execute([$election_id, $election_id, 1, 2]);
		return $statement->rowCount();
	}

	// GET THE TOTAL NUMBER OF CONTESTANTS UNDER STARTED ELECTION
	function count_contestants_on_runing_election($election_id) {
		global $conn;
		$query = "
		    SELECT * FROM cont_details 
		    INNER JOIN election 
		    WHERE election.eid = ? 
		    AND cont_details.election_name = ?";
		$statement = $conn->prepare($query);
		$statement->execute([$election_id, $election_id]);
		return $statement->rowCount();
	}

	// GET THE TOTAL NUMBER OF POSITIONS UNDER STARTED ELECTION
	function count_positions_on_running_election($election_id) {
		global $conn;
		$query = "SELECT * FROM positions INNER JOIN election WHERE election.eid = ? AND positions.election_id = ?";
		$statement = $conn->prepare($query);
		$statement->execute([$election_id, $election_id]);
		return $statement->rowCount();
	}

	// GET THE NUMBER OF APPLIED APPLICANTS (NOT-VERIFIED)
	function count_votes_on_runing_election($election_id) {
		global $conn;
		$query = "SELECT COUNT(*) as all_voterhasdone FROM voterhasdone INNER JOIN election ON election.eid = ? WHERE voterhasdone.election_id = ?";
		$statement = $conn->prepare($query);
		$statement->execute([$election_id, $election_id]);
		foreach ($statement->fetchAll() as $row) {
			return $row['all_voterhasdone'];
		}
	}


?>