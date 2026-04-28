<?php
require 'connection/conn.php';

try {
    // 1. Rename columns in registrars
    echo "Renaming columns...\n";
    $conn->exec("ALTER TABLE registrars CHANGE voter_id uuid VARCHAR(100)");
    $conn->exec("ALTER TABLE registrars CHANGE std_id voter_id VARCHAR(100)");
    $conn->exec("ALTER TABLE registrars CHANGE std_password password VARCHAR(255)");
    $conn->exec("ALTER TABLE registrars CHANGE std_fname first_name VARCHAR(100)");
    $conn->exec("ALTER TABLE registrars CHANGE std_lname last_name VARCHAR(100)");
    $conn->exec("ALTER TABLE registrars CHANGE std_gender gender ENUM('male', 'female') DEFAULT 'male'");
    $conn->exec("ALTER TABLE registrars CHANGE std_email email VARCHAR(200)");
    $conn->exec("ALTER TABLE registrars CHANGE vote has_voted ENUM('no', 'yes') DEFAULT 'no'");

    // 2. Rename the table
    echo "Renaming table...\n";
    $conn->exec("RENAME TABLE registrars TO voters");

    echo "Migration successful: registrars table restructured to voters table.\n";

} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
