<?php

	// Connection To Database
	require dirname(__DIR__)  . '/bootstrap.php';

	$driver = $_ENV['DB_DRIVER'];
    $hostname = $_ENV['DB_HOST'];
    $port = $_ENV['DB_PORT'];
    $database = $_ENV['DB_DATABASE'];
    $username = $_ENV['DB_USERNAME'];
    $password = $_ENV['DB_PASSWORD'];

    try {
        $string = $driver . ":host=" . $hostname . ";charset=utf8mb4;dbname=" . $database;
        $conn = new \PDO(
            $string, $username, $password
        );
    } catch (\PDOException $e) {
        exit($e->getMessage());
    }
    session_start();

    date_default_timezone_set('Africa/Accra');
    require_once($_SERVER['DOCUMENT_ROOT'].'/puubu/config.php');
    require_once(BASEURL.'helpers/helpers.php');

	// 
	if (!is_dir(__DIR__ . '/cache')) mkdir(__DIR__ . '/cache', 0755, true);
	if (!is_dir(__DIR__ . '/logs')) mkdir(__DIR__ . '/logs', 0755, true);

 	// GRAP VISITOR OR USER INFO
 	require_once (BASEURL . 'vendor/autoload.php');
	use ipinfo\ipinfo\IPinfo;

	$access_token = IPINFO_PRIVATE_KEY;
	$client = new IPinfo($access_token, ['timeout' => 5]); // Optional: increase timeout

	// Optional: cache file path
	$cacheFile = __DIR__ . '/cache/ipinfo_' . $_SERVER['REMOTE_ADDR'] . '.json';

	$location = "Location unavailable due to network issue"; // Default fallback
	$details = null; // ✅ Ensure it's defined

	// Check if cached data exists and is recent (e.g., within 1 hour)
	if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < 3600)) {
		$cached = json_decode(file_get_contents($cacheFile), true);
		if ($cached && isset($cached['city'], $cached['region'], $cached['country'])) {
			$location = $cached['city'] . ', ' . $cached['region'] . ', ' . $cached['country'] . $cached['ip'];
		}
		
		// If no cache, create a fallback object
		if (!$details) {
			$details = (object) [
				'ip' => $_SERVER['REMOTE_ADDR'],
				'city' => null,
				'region' => null,
				'country' => null,
				'loc' => null,
				'org' => null,
				'timezone' => null
			];
		}
		// Log the error
    	// file_put_contents(__DIR__ . '/logs/ipinfo_errors.log', date('Y-m-d H:i:s') . " - " . $e->getMessage() . "\n", FILE_APPEND);


	} else {
		try {
			$details = $client->getDetails();
			if (isset($details->city, $details->region, $details->country, $details->ip)) {
				$location = $details->city . ', ' . $details->region . ', ' . $details->country . ', ' . $details->ip;
			}

			// Save to cache
			file_put_contents($cacheFile, json_encode([
				'city' => $details->city,
				'region' => $details->region,
				'country' => $details->country, 
				'ip' => $details->ip
			]));
		} catch (IPinfoException $e) {
			// Log error silently
			$logMessage = date('Y-m-d H:i:s') . " - IPinfo error: " . $e->getMessage() . "\n";
			file_put_contents(__DIR__ . '/logs/ipinfo_errors.log', $logMessage, FILE_APPEND);
		}
	}

	// try {

	// 	$details = $client->getDetails();
	// 	$location = $details->city . ', ' . $details->region . ', ' . $details->country;
	// } catch (IPinfoException $e) {
	// 	// Log the error silently
	// 	error_log("IPinfo error: " . $e->getMessage());

	// 	// Fallback message for user
	// 	$location = "Location unavailable due to network issue";
	// }



 	if (isset($_SESSION['crAdmin'])) {
 		$data = array($_SESSION['crAdmin']);
 		$sql = "SELECT * FROM puubu_admin WHERE admin_id = ? LIMIT 1";
 		$statement = $conn->prepare($sql);
 		$statement->execute($data);
		$admin_dt = $statement->fetchAll();

		if ($statement->rowCount() > 0) {
			$admin_data = $admin_dt[0];
			$admin_id = $admin_data['admin_id'];
			$fullName = ucwords($admin_data['cfname'] . ' ' . $admin_data['clname']);
 			$lName = ucwords($admin_data['clname']);
 			$fname = ucwords($admin_data['cfname']);
		} else {
			redirect(PROOT . '172.06.84.0');
		}

 	}

 	if (isset($_SESSION['voter_accessed'])) {
 		// code...
	 	$voterId = $_SESSION['voter_accessed'];
		$voterQuery = "
		    SELECT * FROM registrars 
		    INNER JOIN election 
		    ON election.election_id = registrars.registrar_election 
		    WHERE registrars.voter_id = ? 
		    AND election.election_id = registrars.registrar_election
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
 	 	$flash = '<div class="bg-success" id="temporary" style="color: #fff;"><p class="text-center">'.$_SESSION['flash_success'].'</p></div>';
 	 	unset($_SESSION['flash_success']);
 	 }

 	 if (isset($_SESSION['flash_error'])) {
 	 	$flash = '<div class="bg-danger" id="temporary" style="color: #fff;"><p class="text-center">'.$_SESSION['flash_error'].'</p></div>';
 	 	unset($_SESSION['flash_error']);
 	}




?>
