<?php
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$conn = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function guidv4() {
    if (function_exists('com_create_guid') === true) {
        return trim(com_create_guid(), '{}');
    }
    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

try {
    // 1. Get an existing admin to act as organizer
    $stmt = $conn->query("SELECT admin_id FROM puubu_admin LIMIT 1");
    $admin_id = $stmt->fetchColumn();

    if (!$admin_id) {
        // Create an admin if none exists
        $admin_id = guidv4();
        $hashed = password_hash('password', PASSWORD_DEFAULT);
        $conn->prepare("INSERT INTO puubu_admin (admin_id, cfname, clname, cemail, ckey, role) VALUES (?, 'Test', 'Admin', 'admin@example.com', ?, 'super_admin')")->execute([$admin_id, $hashed]);
    }

    // 2. Create an Election
    $election_id = guidv4();
    $election_name = "SRC General Election 2026";
    $election_by = "Student Representative Council";
    $conn->prepare("INSERT INTO election (election_id, election_name, election_by, organizer_id, session) VALUES (?, ?, ?, ?, 0)")->execute([$election_id, $election_name, $election_by, $admin_id]);
    echo "Created Election: $election_name\n";

    // 3. Create Positions
    $positions = ['President', 'Vice President'];
    $position_ids = [];
    foreach ($positions as $pos) {
        $pid = guidv4();
        $position_ids[$pos] = $pid;
        $conn->prepare("INSERT INTO positions (position_id, position_name, election_id) VALUES (?, ?, ?)")->execute([$pid, $pos, $election_id]);
        echo "Created Position: $pos\n";
    }

    // 4. Create Contestants
    $contestants = [
        ['fname' => 'John', 'lname' => 'Doe', 'gender' => 'male', 'pos' => 'President', 'ballot' => 1],
        ['fname' => 'Jane', 'lname' => 'Smith', 'gender' => 'female', 'pos' => 'President', 'ballot' => 2],
        ['fname' => 'Robert', 'lname' => 'Johnson', 'gender' => 'male', 'pos' => 'Vice President', 'ballot' => 1],
        ['fname' => 'Emily', 'lname' => 'Davis', 'gender' => 'female', 'pos' => 'Vice President', 'ballot' => 2]
    ];

    foreach ($contestants as $c) {
        $cid = guidv4();
        $pid = $position_ids[$c['pos']];
        // using ui-avatars as a placeholder profile
        $profile = "https://ui-avatars.com/api/?name=" . urlencode($c['fname'] . ' ' . $c['lname']) . "&background=random";
        
        $conn->prepare("INSERT INTO cont_details (contestant_id, cont_fname, cont_lname, cont_gender, contestant_ballot_number, cont_position, contestant_election, cont_profile) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")
             ->execute([$cid, $c['fname'], $c['lname'], $c['gender'], $c['ballot'], $pid, $election_id, $profile]);
             
        // Initialize vote counts
        $conn->prepare("INSERT INTO vote_counts (vote_count_id, results, results_no, contestant_id, position_id, election_id) VALUES (?, 0, 0, ?, ?, ?)")
             ->execute([guidv4(), $cid, $pid, $election_id]);
             
        echo "Created Contestant: {$c['fname']} {$c['lname']} for {$c['pos']}\n";
    }

    // 5. Create Voters
    echo "\nGenerating 10 Voters...\n";
    echo "-------------------------\n";
    echo sprintf("%-15s | %-10s\n", "Voter ID", "Password");
    echo "-------------------------\n";

    $firstNames = ['Michael', 'Sarah', 'James', 'Jessica', 'David', 'Ashley', 'William', 'Amanda', 'Richard', 'Melissa'];
    $lastNames = ['Brown', 'Wilson', 'Moore', 'Taylor', 'Anderson', 'Thomas', 'Jackson', 'White', 'Harris', 'Martin'];

    for ($i = 0; $i < 10; $i++) {
        $vid = guidv4();
        $std_id = 'STD' . rand(10000, 99999);
        $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        
        $fname = $firstNames[$i];
        $lname = $lastNames[$i];
        $email = strtolower($fname . '.' . $lname . '@example.com');

        $conn->prepare("INSERT INTO registrars (voter_id, std_id, std_password, std_fname, std_lname, std_email, registrar_election) VALUES (?, ?, ?, ?, ?, ?, ?)")
             ->execute([$vid, $std_id, $hashed, $fname, $lname, $email, $election_id]);

        echo sprintf("%-15s | %-10s\n", $std_id, $password);
    }
    echo "-------------------------\n";
    echo "Dummy data generated successfully!\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
