<?php 

	// Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
	
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
function sms_otp($msg, $phone) {
	$sender = urlencode("Inqoins VER");
    $api_url = "https://api.innotechdev.com/sendmessage.php?key=".SMS_API_KEY."&message={$msg}&senderid={$sender}&phone={$phone}";
    $json_data = file_get_contents($api_url);
    $response_data = json_decode($json_data);
    // Can be use for checks on finished / unfinished balance
    $fromAPI = 'insufficient balance, kindly credit your account';  
    if ($api_url)
    	return 1;
	else
		return 0;
}

// Send EMAIL
	function send_email($to, $subject, $body) {
		$mail = new PHPMailer(true);
		try {
	        $from = MAIL_EMAIL;
	        $from_name = 'Kokuromotie Group';

	        $mail->IsSMTP();
	        $mail->SMTPAuth = true;
	        $mail->SMTPSecure = 'ssl'; 
	        $mail->Host = MAIL_HOST;
	        $mail->Port = MAIL_PORT;  
	        $mail->Username = $from;
	        $mail->Password = MAIL_KEY; 

	        $mail->IsHTML(true);
	        $mail->WordWrap = 50;
	        $mail->From = $from;
	        $mail->FromName = $from_name;
	        $mail->Sender = $from;
	        $mail->AddReplyTo($from, $from_name);
	        $mail->Subject = $subject;
	        $mail->Body = $body;
	        $mail->AddAddress($to);
	        $mail->send();
	        return true;
	    } catch (\Exception $e) {
	    	$logDir = dirname(__DIR__) . '/connection/logs';
	    	if (!is_dir($logDir)) mkdir($logDir, 0777, true);
	    	$errorMsg = date('Y-m-d H:i:s') . " - Mail Error: " . $mail->ErrorInfo . " (Exception: " . $e->getMessage() . ")\n";
	    	file_put_contents($logDir . '/mail_errors.log', $errorMsg, FILE_APPEND);
	    	return false;
	    }
	}

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

	function convertAndOverwriteToJpeg($sourcePath) {
		// Detect real mime type
		$mime = mime_content_type($sourcePath);

		switch ($mime) {
			case 'image/jpeg':
				$image = imagecreatefromjpeg($sourcePath);
				break;
			case 'image/png':
				$image = imagecreatefrompng($sourcePath);
				break;
			case 'image/gif':
				$image = imagecreatefromgif($sourcePath);
				break;
			case 'image/webp':
				$image = imagecreatefromwebp($sourcePath);
				break;
			default:
				throw new Exception("Unsupported image type: $mime");
		}

		if (!$image) {
			throw new Exception("Invalid or corrupted image file.");
		}
		// Overwrite original file but force .jpg extension
		$targetPath = preg_replace('/\\.[^.]+$/', '', $sourcePath) . ".jpg";

		// Save as true JPEG
		imagejpeg($image, $targetPath, 90);
		imagedestroy($image);

		// Optionally delete old file if extension changed
		if ($targetPath !== $sourcePath && file_exists($sourcePath)) {
			unlink($sourcePath);
		}

		return $targetPath;
	}













	// Sessions For login
	function cAdminLoggedInID($admin_id) {
		$_SESSION['crAdmin'] = $admin_id;
		global $conn;
		
		// Check for 2FA status
		$query2fa = "SELECT is_2fa_enabled, google_auth_secret FROM admins WHERE uuid = :admin_id";
		$stmt2fa = $conn->prepare($query2fa);
		$stmt2fa->execute([':admin_id' => $admin_id]);
		$adminData = $stmt2fa->fetch();

		$data = array(
			':last_login' => date("Y-m-d H:i:s"),
			':id' => $admin_id
		);
		$query = "UPDATE admins SET last_login = :last_login WHERE uuid = :id";
		$statement = $conn->prepare($query);
		$result = $statement->execute($data);
		
		if (isset($result)) {
			if ($adminData['is_2fa_enabled'] == 1) {
				$_SESSION['2fa_pending'] = true;
				redirect(PROOT . 'admin/verify-2fa');
			} else {
				$message = "logged into the system";
				add_to_log($message, $admin_id, 'admin');
				$_SESSION['flash_success'] = 'You are now logged in! Please consider setting up 2FA in settings.';
				redirect(PROOT . 'admin');
			}
		}
	}

	function cadminIsLoggedIn() {
		if (isset($_SESSION['crAdmin']) && $_SESSION['crAdmin'] > 0) {
			if (isset($_SESSION['2fa_pending']) && $_SESSION['2fa_pending'] === true) {
				return false;
			}
			return true;
		}
		return false;
	}

	// Redirect If not Logged in
	function cadminLoginErrorRedirect($url = 'signin') {
		$_SESSION['flash_error'] = '<div class="text-center" id="temporary" style="margin-top: 60px;">Oops... you must be logged in to access that page.</div>';
		redirect(PROOT . 'admin/' . $url);
	}

	function admin_permission_error_redirect($url = 'signin'){
		$_SESSION['error_flash'] = '<div class="text-center" style="margin-top: 60px;">You do not have permission to that page.</div>';
		redirect(PROOT . '172.06.84.0/' . $url);
	}

	// GET ADMIN PROFILE DETAILS
	function get_admin_profile($admin_id) {
		global $conn;
		$output = '';

		$query = "
			SELECT * FROM admins 
			WHERE uuid = :uuid AND trash = :trash 
			LIMIT 1
		";
		$statement = $conn->prepare($query);
		$statement->execute([':uuid' => $admin_id, ':trash' => 0]);
		$admin_row = $statement->fetch();

		if ($admin_row) {
				$output = '
					<h6>First Name</h6>
				    <p class="lead text-info">'.ucwords($admin_row["first_name"]).'</p>
				    <br>
					<h6>Last Name</h6>
				    <p class="lead text-info">'.ucwords($admin_row["last_name"]).'</p>
				    <br>
				    <h6>Email</h6>
				    <p class="lead text-info">'.$admin_row["email"].'</p>
				    <br>
				    <h6>Joined Date</h6>
				    <p class="lead text-info">'.pretty_date($admin_row["created_at"]).'</p>
				    <br>
				    <h6>Last Login</h6>
				    <p class="lead text-info">'.pretty_date($admin_row["last_login"]).'</p>
				';
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
  	$queryS = "SELECT * FROM election WHERE status = '1' OR status = '2' LIMIT 1";
  	$statement = $conn->prepare($queryS);
  	$statement->execute();
  	$stated_election_result = $statement->fetchAll();
  	$started_election = $statement->rowCount();
  	foreach ($stated_election_result as $sub_row) {}

	// GET THE TOTAL NUMBER OF VOTERS
	function count_voters() {
		global $conn;
		$query = "SELECT * FROM `voters` INNER JOIN election ON election.uuid = voters.election_uuid";
		$statement = $conn->prepare($query);
		$statement->execute();
		return $statement->rowCount();
	}


	// GET THE TOTAL NUMBER OF CONTESTANTS UNDER STARTED ELECTION
	function count_contestants() {
		global $conn;
		$query = "SELECT * FROM cont_details INNER JOIN election WHERE election.uuid = cont_details.election_uuid AND election.status = '1'";
		$statement = $conn->prepare($query);
		$statement->execute();
		return $statement->rowCount();
	}

	// GET THE TOTAL NUMBER OF POSITIONS UNDER STARTED ELECTION
	function count_positions() {
		global $conn;
		$query = "SELECT * FROM positions INNER JOIN election WHERE election.uuid = positions.election_uuid AND election.status = '1'";
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
	function count_voters_on_runing_election($uuid) {
		global $conn;
		$query = "SELECT * FROM `voters` INNER JOIN election ON election.uuid = ? AND voters.election_uuid = ? WHERE election.status = ? OR election.status = ?";
		$statement = $conn->prepare($query);
		$statement->execute([$uuid, $uuid, 1, 2]);
		return $statement->rowCount();
	}

	// GET THE TOTAL NUMBER OF CONTESTANTS UNDER STARTED ELECTION
	function count_contestants_on_runing_election($uuid) {
		global $conn;
		$query = "
		    SELECT * FROM cont_details 
		    INNER JOIN election 
		    WHERE election.uuid = ? 
		    AND cont_details.election_uuid = ?";
		$statement = $conn->prepare($query);
		$statement->execute([$uuid, $uuid]);
		return $statement->rowCount();
	}

	// GET THE TOTAL NUMBER OF POSITIONS UNDER STARTED ELECTION
	function count_positions_on_running_election($uuid) {
		global $conn;
		$query = "SELECT * FROM positions INNER JOIN election WHERE election.uuid = ? AND positions.election_uuid = ?";
		$statement = $conn->prepare($query);
		$statement->execute([$uuid, $uuid]);
		return $statement->rowCount();
	}

	// GET THE NUMBER OF APPLIED APPLICANTS (NOT-VERIFIED)
	function count_votes_on_runing_election($uuid) {
		global $conn;
		$query = "SELECT COUNT(*) as all_voterhasdone FROM voterhasdone INNER JOIN election ON election.uuid = ? WHERE voterhasdone.election_uuid = ?";
		$statement = $conn->prepare($query);
		$statement->execute([$uuid, $uuid]);
		foreach ($statement->fetchAll() as $row) {
			return $row['all_voterhasdone'];
		}
	}


	// get user details
	function get_voter_details($param, $person) {
		global $conn;

		$sql = "SELECT * FROM voters WHERE ";
		if ($param == 'id') {
			$sql .= "uuid = '".$person."'";
		} else if ($param == 'email') {
			$sql .= "email = '".$person."'";
		} else if ($param == 'voter_id') {
			$sql .= "voter_id = '".$person."'";
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

		$uuid = guidv4();
		$sql = "
			INSERT INTO `activity_logs`(`uuid`, `log_message`, `user_uuid`, `log_type`) 
			VALUES (?, ?, ?, ?)
		";
		$statement = $conn->prepare($sql);
		$result = $statement->execute([$uuid, $message, $person, $type]);

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
		// 	$where = ' WHERE activity_logs.user_uuid = "'.$admin.'" AND CAST(activity_logs.createdAt AS date) = "' . $today . '"';
		// }

		$sql = "
			SELECT * FROM activity_logs 
			-- INNER JOIN admins 
			-- ON admins.uuid = activity_logs.user_uuid
		$where 
			ORDER BY activity_logs.createdAt DESC
			LIMIT 10
		";
		$statement = $conn->prepare($sql);
		$statement->execute();
		$rows = $statement->fetchAll();

	if ($statement->rowCount() > 0): 
		foreach ($rows as $row) {
			
			$person = '';
			if ($row['log_type'] == 'user') {
				$person = 'voter';
				$persons = get_voter_details('std_id', $row["user_uuid"]);
				if (is_array($persons)) {
					//dnd($persons);
					$person = ucwords($persons['std_fname'] . ' ' . $persons['std_lname']);
				}
			}

			$output .= '
				<li data-icon="account_circle">
					<div>
						<h6 class="fs-base mb-1">' . (($row["user_uuid"] == $admin) ? 'You': $person) . ' <span class="fs-sm fw-normal text-body-secondary ms-1">' . pretty_date($row["createdAt"]) .'</span></h6>
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
    //     $where = ' WHERE admins.uuid = "' . $admin . '" AND CAST(activity_logs.createdAt AS date) = "' . $today . '" ';
    // }

    $sql = "
        SELECT * FROM activity_logs 
        -- INNER JOIN admins 
        -- ON admins.uuid = activity_logs.user_uuid
        $where 
        -- ORDER BY activity_logs.createdAt DESC
    ";
    $statement = $conn->prepare($sql);
    $statement->execute();

    return $statement->rowCount();
}

// CSRF Protection
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function verify_csrf_token($token) {
    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        return true;
    }
    return false;
}

?>

