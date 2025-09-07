<?php 

	// IMPORT CSV FILE TO DATABASE

	header('Content-type: text/html; chaset=utf-8');
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");


	set_time_limit(0);
	ob_implicit_flush(1);

	require_once("../../connection/conn.php");
	
	if (isset($_SESSION['csv_file_name'])) {

		$location = BASEURL . 'media/uploadedregistrars/' . $_SESSION['csv_file_name'];
		$file_data = fopen($location, 'r');
		fgetcsv($file_data);

		while($row = fgetcsv($file_data)) {

		    $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_';
		    $generatedpassword = substr(str_shuffle($string), 0, 8);
			$data = array(
				':std_id' => $row[0],
				':std_password' => $generatedpassword,
				':std_fname' => $row[1],
				':std_lname' => $row[2],
				':std_email' => $row[3],
				':election_type' => $row[4]
			);

			$query = "INSERT INTO registrars (std_id, std_password, std_fname, std_lname, std_email, election_type) VALUES (:std_id, :std_password, :std_fname, :std_lname, :std_email, :election_type)";
			$statement = $conn->prepare($query);
			$statement->execute($data);
			sleep(1);

			if (ob_get_level() > 0) {
				ob_end_flush();
			}
		}
		unset($_SESSION['csv_file_name']);
	}



 ?>