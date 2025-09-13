<?php 


	// print out results for development usages
	function dnd($data) {
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		die;
	}

	// Make Date Readable
	function pretty_date($date) {
		if ($date != null ||$date != '') 
			return date("M d, Y h:i A", strtotime($date));

		return false;
	}

	// get only date from full datetime
	function pretty_date_only($date) {
		if ($date != null ||$date != '') 
			return date("F j, Y", strtotime($date));

		return false;
	}

	// extract time from full date
	function time_from_date($date) {
		$dt = new DateTime($date);

		$date = $dt->format('d-m-Y');
		$time = $dt->format('h:i:s A');

		return $time;
	}

	// Display money in a readable way
function money($number) {
	$output = '0.00';
	if ($number != NULL || $number != '') 
		$output = number_format($number, 2);

	return '₵' . $output;
}

// Check For Incorrect Input Of Data
function sanitize($dirty) {
    $clean = htmlentities($dirty, ENT_QUOTES, "UTF-8");
    return trim($clean);
}

function cleanPost($post) {
    $clean = [];
    foreach ($post as $key => $value) {
      	if (is_array($value)) {
        	$ary = [];
        	foreach($value as $val) {
          		$ary[] = sanitize($val);
        	}
        	$clean[$key] = $ary;
      	} else {
        	$clean[$key] = sanitize($value);
      	}
    }
    return $clean;
}

//
function php_url_slug($string) {
 	$slug = preg_replace('/[^a-z0-9-]+/', '-', trim(strtolower($string)));
 	return $slug;
}

// REDIRECT PAGE
function redirect($url) {
    if(!headers_sent()) {
      	header("Location: {$url}");
    } else {
      	echo '<script>window.location.href="' . $url . '"</script>';
    }
    exit;
}

function issetElse($array, $key, $default = "") {
    if(!isset($array[$key]) || empty($array[$key])) {
      return $default;
    }
    return $array[$key];
}


// Email VALIDATION
function isEmail($email) {
	return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) ? FALSE : TRUE;
}

// GET USER IP ADDRESS
function getIPAddress() {  
    //whether ip is from the share internet  
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  
        $ip = $_SERVER['HTTP_CLIENT_IP'];  
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  // whether ip is from the proxy
       $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
     } else {  // whether ip is from the remote address 
        $ip = $_SERVER['REMOTE_ADDR'];  
    }  
    return $ip;  
}

// PRINT OUT RANDAM NUMBERS
function digit_random($digits) {
  	return rand(pow(10, $digits - 1) - 1, pow(10, $digits) - 1);
}

function js_alert($msg) {
	return '<script>alert("' . $msg . '");</script>';
}


// 
// function sms_otp($msg, $phone) {
// 	$sender = urlencode("Inqoins VER");
//     $api_url = "https://api.innotechdev.com/sendmessage.php?key=".SMS_API_KEY."&message={$msg}&senderid={$sender}&phone={$phone}";
//     $json_data = file_get_contents($api_url);
//     $response_data = json_decode($json_data);
//     // Can be use for checks on finished / unfinished balance
//     $fromAPI = 'insufficient balance, kindly credit your account';  
//     if ($api_url)
//     	return 1;
// 	else
// 		return 0;
// }

//
// function send_email($name, $to, $subject, $body) {
// 	$mail = new PHPMailer(true);
// 	try {
//         $fn = $name;
//         $to = $to;
//         $from = MAIL_MAIL;
//         $from_name = 'Garypie, Shop.';
//         $subject = $subject;
//         $body = $body;

//         //Create an instance; passing `true` enables exceptions
//         $mail = new PHPMailer(true);

//         $mail->IsSMTP();
//         $mail->SMTPAuth = true;

//         $mail->SMTPSecure = 'ssl'; 
//         $mail->Host = 'smtp.garypie.com';
//         $mail->Port = 465;  
//         $mail->Username = $from;
//         $mail->Password = MAIL_KEY; 

//         $mail->IsHTML(true);
//         $mail->WordWrap = 50;
//         $mail->From = $from;
//         $mail->FromName = $from_name;
//         $mail->Sender = $from;
//         $mail->AddReplyTo($from, $from_name);
//         $mail->Subject = $subject;
//         $mail->Body = $body;
//         $mail->AddAddress($to);
//         $mail->send();
//         return true;
//     } catch (Exception $e) {
//     	//return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
//     	return false;
//         //$message = "Please check your internet connection well...";
//     }
// }

// Generate UUID VERSION 4
function guidv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

/// find user agent
function getBrowserAndOs() {

    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $browser = "N/A";

    $browsers = array(
        '/msie/i' => 'Internet explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/edge/i' => 'Edge',
        '/opera/i' => 'Opera',
        '/mobile/i' => 'Mobile browser'
    );

    foreach ($browsers as $regex => $value) {
        if (preg_match($regex, $user_agent)) { $browser = $value; }
    }

    $visitor_agent_division = explode("(", $user_agent)[1];
    list($os, $division_two) = explode(")", $visitor_agent_division);

    $refferer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

    $visitor_broswer_os = array(
        'browser' => $browser,
        'operatingSystem' => $os,
        'refferer' => $refferer
    );

   	$output = json_encode($visitor_broswer_os);

    return $output;
}

// get user/visitor device
function getDeviceType() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // Check if it's a mobile device
    if (preg_match('/mobile/i', $userAgent)) {
        if (preg_match('/android/i', $userAgent)) {
            return "Mobile (Android)";
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            return "Mobile (iOS)";
        } else {
            return "Mobile (Other)";
        }
    }

    // Check if it's a tablet
    if (preg_match('/tablet|ipad/i', $userAgent)) {
        return "Tablet";
    }

    // Default to desktop
    return "Desktop";
} 

function goBack() {
	// $previous = "javascript:history.go(-1)";
	$previous = "javascript:history.back()";
	// if (isset($_SERVER['HTTP_REFERER'])) {
	//     $previous = $_SERVER['HTTP_REFERER'];
	// }
	return $previous;
}


	function idle_user() {

		// Check the last activity time
		if (isset($_SESSION['last_activity'])) {
			$idleTime = time() - $_SESSION['last_activity'];

			// If the idle time exceeds the timeout period
			if ($idleTime > IDLE_TIMEOUT) {
				// Destroy the session and log out the user
				//session_unset();
				//session_destroy();

				// Redirect to the login page or show a message
				// $_SESSION['flash_error'] = 'Session expired. Please log in again!';
				//redirect(PROOT . 'auth/login');
				//exit;
				return false;
			}
		}

		// Update the last activity timestamp
		$_SESSION['last_activity'] = time();
		return true;
	}
















	// Sessions For login
	function cAdminLoggedInID($admin_id) {
		$_SESSION['crAdmin'] = $admin_id;
		global $conn;
		$data = array(
			':last_login' => date("Y-m-d H:i:s"),
			':id' => $admin_id
		);
		$query = "UPDATE puubu_admin SET last_login = :last_login WHERE id = :id";
		$statement = $conn->prepare($query);
		$result = $statement->execute($data);
		if (isset($result)) {
			$message = "logged into the system";
			add_to_log($message, $admin_id, 'admin');

			$_SESSION['flash_success'] = 'You are now logged in!';
			redirect(PROOT . '172.06.84.0/index');
		}
	}

	function cadminIsLoggedIn() {
		if (isset($_SESSION['crAdmin']) && $_SESSION['crAdmin'] > 0) {
			return true;
		}
		return false;
	}

	// Redirect If not Logged in
	function cadminLoginErrorRedirect($url = 'signin') {
		$_SESSION['flash_error'] = '<div class="text-center" id="temporary" style="margin-top: 60px;">Oops... you must be logged in to access that page.</div>';
		redirect(PROOT . '172.06.84.0/' . $url);
	}

	function admin_permission_error_redirect($url = 'signin'){
		$_SESSION['error_flash'] = '<div class="text-center" style="margin-top: 60px;">You do not have permission to that page.</div>';
		redirect(PROOT . '172.06.84.0/' . $url);
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
			if ($admin_row['id'] == $row['id']) {
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
		$query = "SELECT * FROM `registrars` INNER JOIN election ON election.election_id = registrars.election_type";
		$statement = $conn->prepare($query);
		$statement->execute();
		return $statement->rowCount();
	}


	// GET THE TOTAL NUMBER OF CONTESTANTS UNDER STARTED ELECTION
	function count_contestants() {
		global $conn;
		$query = "SELECT * FROM cont_details INNER JOIN election WHERE election.election_id = cont_details.election_name AND election.session = '1'";
		$statement = $conn->prepare($query);
		$statement->execute();
		return $statement->rowCount();
	}

	// GET THE TOTAL NUMBER OF POSITIONS UNDER STARTED ELECTION
	function count_positions() {
		global $conn;
		$query = "SELECT * FROM positions INNER JOIN election WHERE election.election_id = positions.election_id AND election.session = '1'";
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
		$query = "SELECT * FROM `registrars` INNER JOIN election ON election.election_id = ? AND registrars.election_type = ? WHERE election.session = ? OR election.session = ?";
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
		    WHERE election.election_id = ? 
		    AND cont_details.election_name = ?";
		$statement = $conn->prepare($query);
		$statement->execute([$election_id, $election_id]);
		return $statement->rowCount();
	}

	// GET THE TOTAL NUMBER OF POSITIONS UNDER STARTED ELECTION
	function count_positions_on_running_election($election_id) {
		global $conn;
		$query = "SELECT * FROM positions INNER JOIN election WHERE election.election_id = ? AND positions.election_id = ?";
		$statement = $conn->prepare($query);
		$statement->execute([$election_id, $election_id]);
		return $statement->rowCount();
	}

	// GET THE NUMBER OF APPLIED APPLICANTS (NOT-VERIFIED)
	function count_votes_on_runing_election($election_id) {
		global $conn;
		$query = "SELECT COUNT(*) as all_voterhasdone FROM voterhasdone INNER JOIN election ON election.election_id = ? WHERE voterhasdone.election_id = ?";
		$statement = $conn->prepare($query);
		$statement->execute([$election_id, $election_id]);
		foreach ($statement->fetchAll() as $row) {
			return $row['all_voterhasdone'];
		}
	}


	///////////////
	// get user details
	function get_voter_details($param, $person) {
		global $conn;

		$sql = "SELECT * FROM registrars WHERE ";
		if ($param = 'id') {
			$sql .= "voter_id = '".$person."'";
		} else if ($param = 'email') {
			$sql .= "std_email = '".$person."'";
		} else if ($param = 'std_id') {
			$sql .= "std_id = '".$person."'";
		}
		$sql .= " AND status = 0 LIMIT 1";
		$statement = $conn->prepare($sql);
		$statement->execute();
		$results = $statement->fetchAll();
		$result = $results[0] ?? '';

		return $result;
	}

	// add to logs
	function add_to_log($message, $person, $type) {
		global $conn;

		$log_id = guidv4();
		$sql = "
			INSERT INTO `puubu_logs`(`log_id`, `log_message`, `log_person`, `log_type`) 
			VALUES (?, ?, ?, ?)
		";
		$statement = $conn->prepare($sql);
		$result = $statement->execute([$log_id, $message, $person, $type]);

		if ($result) {
			return true;
		}
		return false;
	}

	// get logs for admins
	function get_logs($admin) {
		global $conn;
		$output = '';

		$where = '';
		// if (!admin_has_permission()) {
		// 	$where = ' WHERE puubu_logs.log_person = "'.$admin.'" AND CAST(puubu_logs.createdAt AS date) = "' . $today . '"';
		// }

		$sql = "
			SELECT * FROM puubu_logs 
			-- INNER JOIN puubu_admin 
			-- ON puubu_admin.admin_id = puubu_logs.log_person
		$where 
			ORDER BY puubu_logs.createdAt DESC
			LIMIT 10
		";
		$statement = $conn->prepare($sql);
		$statement->execute();
		$rows = $statement->fetchAll();

	if ($statement->rowCount() > 0): 
		foreach ($rows as $row) {
			
			// $persons = explode(' ', $row['admin_fullname']);
			// $person = ucwords($persons[0]);
			if ($row['log_type'] == 'user') {
				$person = 'voter';
				$persons = get_voter_details('std_id', $row["log_person"]);
				if (is_array($persons)) {
					$person = ucwords($persons['std_fname'] . ' ' . $person['std_lname']);
				}
			}

			$output .= '
				<li data-icon="account_circle">
					<div>
						<h6 class="fs-base mb-1">' . (($row["log_person"] == $admin) ? 'You': $person) . ' <span class="fs-sm fw-normal text-body-secondary ms-1">' . pretty_date($row["createdAt"]) .'</span></h6>
						<p class="mb-0">' . $row["log_message"] . '</p>
					</div>
				</li>
			';
		}
	else:
		$output .= '
				<div class="alert alert-info">
					No data found!
				</div>
			';
	endif;

	return $output;
}

// count logs
function count_logs($admin) {
	global $conn;
	// $today = date("Y-m-d");

    $where = '';
    // if (!admin_has_permission()) {
    //     $where = ' WHERE puubu_admin.admin_id = "' . $admin . '" AND CAST(puubu_logs.createdAt AS date) = "' . $today . '" ';
    // }

    $sql = "
        SELECT * FROM puubu_logs 
        -- INNER JOIN puubu_admin 
        -- ON puubu_admin.admin_id = puubu_logs.log_person
        $where 
        -- ORDER BY puubu_logs.createdAt DESC
    ";
    $statement = $conn->prepare($sql);
    $statement->execute();

    return $statement->rowCount();
}



?>