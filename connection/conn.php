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
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    date_default_timezone_set('Africa/Accra');

    // Pseudo-Cron: Auto-close expired elections
    try {
        $autoCloseQuery = "UPDATE election SET status = 2 WHERE status = 1 AND ends_at <= NOW()";
        $conn->exec($autoCloseQuery);
    } catch (\PDOException $e) {
        // Log silently or ignore
    }

    require_once(dirname(__DIR__) . '/config.php');
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
	$remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
	$safeIp = str_replace([':', '.'], '_', $remoteAddr);
	$cacheDir = __DIR__ . '/cache';
	if (!is_dir($cacheDir)) {
		mkdir($cacheDir, 0777, true);
	}
	$cacheFile = $cacheDir . '/ipinfo_' . $safeIp . '.json';

	$location = "Location unavailable due to network issue"; // Default fallback
	$details = null; // ✅ Ensure it's defined

	// Check if cached data exists and is recent (e.g., within 1 hour)
	if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < 3600)) {
		$cached = json_decode(file_get_contents($cacheFile), true);
		if ($cached && isset($cached['ip'])) {
			$location = ($cached['city'] ?? 'Unknown City') . ', ' . ($cached['region'] ?? 'Unknown Region') . ', ' . ($cached['country'] ?? 'Unknown Country') . ', ' . $cached['ip'];
			$details = (object) $cached;
		}
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
		} catch (\Exception $e) {
			// Log error silently
			$logDir = __DIR__ . '/logs';
			if (!is_dir($logDir)) {
				mkdir($logDir, 0777, true);
			}
			$logMessage = date('Y-m-d H:i:s') . " - IPinfo error: " . $e->getMessage() . "\n";
			file_put_contents($logDir . '/ipinfo_errors.log', $logMessage, FILE_APPEND);
		}
	}

	// ✅ FINAL FALLBACK: Ensure $details is always an object with at least the IP
	if (!$details || !is_object($details)) {
		$details = (object) [
			'ip' => $remoteAddr,
			'city' => 'Unknown City',
			'region' => 'Unknown Region',
			'country' => 'Unknown Country'
		];
	}

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
		}
 	}

 	if (isset($_SESSION['voter_accessed'])) {
	 	$voter_uuid = $_SESSION['voter_accessed'];
		$voterQuery = "
		    SELECT v.*, e.title as election_title, e.organized_by, e.starts_at, e.ends_at, e.status as election_status, e.uuid as election_uuid 
		    FROM voters v 
		    INNER JOIN election e 
		    ON e.uuid = v.election_uuid 
		    WHERE v.uuid = ? 
		    LIMIT 1
		";
		$statement = $conn->prepare($voterQuery);
		$statement->execute([$voter_uuid]);
		$voter_count = $statement->rowCount();
		$voter_result = $statement->fetchAll();

		// Check if this voter has already voted in this specific election
		$votedCheck = "SELECT COUNT(*) FROM voterhasdone WHERE voter_id = ? AND election_uuid = ?";
		$vStmt = $conn->prepare($votedCheck);
		$vStmt->execute([$voter_uuid, $voter_result[0]['election_uuid']]);
		$has_voted = $vStmt->fetchColumn() > 0;
 	}

 	// Display on Messages on Errors And Success
	$flash = '';

?>
