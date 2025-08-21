<?php

	// Connection To Database
	// $servername = 'sdb-x.hosting.stackcp.net'; // LIVE
	// $username = 'on_puubu-323134c7c8';
	// $password = 'tczr54co36';

	$servername = 'localhost';
	$username = 'root';
	$password = '';
	$conn = new PDO("mysql:host=$servername;dbname=puubu", $username, $password);
	session_start();

    date_default_timezone_set('Africa/Accra');
    require_once($_SERVER['DOCUMENT_ROOT'].'/puubu/config.php');
    require_once(BASEURL.'helpers/helpers.php');

 	// GRAP VISITOR OR USER INFO
 	require_once (BASEURL . 'vendor/autoload.php');
	use ipinfo\ipinfo\IPinfo;

	$access_token = IPINFO_PRIVATE_KEY;
	$client = new IPinfo($access_token);
	$details = [];//$client->getDetails();

	// $page = "http://".$_SERVER['HTTP_HOST']."".$_SERVER['PHP_SELF'];
	// $page .= iif(!empty($_SERVER['QUERY_STRING']), "?{$_SERVER['QUERY_STRING']}", "");
 // 	$referrer = $_SERVER['HTTP_REFERER'];

	// $user_visitor_query = "
	// 	INSERT INTO puubu_election_logs (election_logs_election_id, election_logs_description, election_logs_page, election_logs_referrer) 
	// 	VALUE ()
	// ";


 	if (isset($_SESSION['crAdmin'])) {
 		$data = array(
 			':c_aid' => (int)$_SESSION['crAdmin']
 		);
 		$sql = "SELECT * FROM puubu_admin WHERE c_aid = :c_aid LIMIT 1";
 		$statement = $conn->prepare($sql);
 		$statement->execute($data);
		$admin_dt = $statement->fetchAll();

		if ($statement->rowCount() > 0) {
			$admin_data = $admin_dt[0];
			$fullName = ucwords($admin_data['cfname'] . ' ' . $admin_data['clname']);
 			$lName = ucwords($admin_data['clname']);
 			$fname = ucwords($admin_data['cfname']);
		} else {
			redirect(PROOT);
		}

 	}

 	if (isset($_SESSION['voter_accessed'])) {
 		// code...
	 	$voterId = $_SESSION['voter_accessed'];
		$voterQuery = "
		    SELECT * FROM registrars 
		    INNER JOIN election 
		    ON election.eid = registrars.election_type 
		    WHERE registrars.id = ? 
		    AND election.eid = registrars.election_type
		    LIMIT 1
		";
		$statement = $conn->prepare($voterQuery);
		$statement->execute([$voterId]);
		$voter_count = $statement->rowCount();
		$voter_result = $statement->fetchAll();
 	}


 	// Display on Messages on Errors And Success
	$flash = '';
 	if (isset($_SESSION['flash_success'])) {
 	 	$flash = '<div class="bg-success" id="temporary" style="margin-top: 60px; color: #fff;"><p class="text-center">'.$_SESSION['flash_success'].'</p></div>';
 	 	unset($_SESSION['flash_success']);
 	 }

 	 if (isset($_SESSION['flash_error'])) {
 	 	$flash = '<div class="bg-danger" id="temporary" style="margin-top: 60px; color: #fff;"><p class="text-center">'.$_SESSION['flash_error'].'</p></div>';
 	 	unset($_SESSION['flash_error']);
 	}




?>
