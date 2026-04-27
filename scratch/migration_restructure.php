<?php
require 'connection/conn.php';

try {
    $conn->beginTransaction();

    // 1. Update election table
    // Rename columns
    $conn->exec("ALTER TABLE election CHANGE election_id uuid VARCHAR(100)");
    $conn->exec("ALTER TABLE election CHANGE organizer_id organizer_id VARCHAR(100)"); // Stay same but for consistency
    $conn->exec("ALTER TABLE election CHANGE election_name title VARCHAR(225)");
    $conn->exec("ALTER TABLE election CHANGE election_by organized_by VARCHAR(255)");
    $conn->exec("ALTER TABLE election CHANGE added_date created_at DATETIME");
    $conn->exec("ALTER TABLE election CHANGE start_date starts_at DATETIME");
    $conn->exec("ALTER TABLE election CHANGE end_date ends_at DATETIME");
    $conn->exec("ALTER TABLE election CHANGE session status TINYINT(4)");
    $conn->exec("ALTER TABLE election CHANGE etrash is_deleted TINYINT(4)");
    $conn->exec("ALTER TABLE election CHANGE election_manual_stop_time manual_stopped_at DATETIME");

    // Drop redundant column
    $conn->exec("ALTER TABLE election DROP COLUMN stop_timer");

    // 2. Update Related Tables (Foreign Keys)
    $conn->exec("ALTER TABLE registrars CHANGE registrar_election election_uuid VARCHAR(100)");
    $conn->exec("ALTER TABLE positions CHANGE election_id election_uuid VARCHAR(100)");
    $conn->exec("ALTER TABLE cont_details CHANGE contestant_election election_uuid VARCHAR(100)");
    $conn->exec("ALTER TABLE vote_counts CHANGE election_id election_uuid VARCHAR(100)");
    $conn->exec("ALTER TABLE voterhasdone CHANGE election_id election_uuid VARCHAR(100)");

    $conn->commit();
    echo "Database migration completed successfully.";
} catch (\Exception $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    echo "Migration failed: " . $e->getMessage();
}
