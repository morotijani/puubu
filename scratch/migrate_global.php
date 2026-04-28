<?php
require 'connection/conn.php';

try {
    // 1. Rename Tables
    $tables = [
        'puubu_admin' => 'admins',
        'cont_details' => 'contestants',
        'vote_counts' => 'results',
        'voterhasdone' => 'voter_participation',
        'voter_login_details' => 'voter_security_logs',
        'puubu_logs' => 'activity_logs'
    ];

    /*
    foreach ($tables as $old => $new) {
        $conn->exec("RENAME TABLE `$old` TO `$new` ");
        echo "Renamed table $old to $new\n";
    }
    */

    // 2. Restructure 'admins'
    $conn->exec("ALTER TABLE admins 
        CHANGE admin_id uuid VARCHAR(100),
        CHANGE cfname first_name VARCHAR(100),
        CHANGE clname last_name VARCHAR(100),
        CHANGE cemail email VARCHAR(200),
        CHANGE ckey password VARCHAR(255),
        CHANGE joined_date created_at DATETIME DEFAULT CURRENT_TIMESTAMP");
    echo "Restructured 'admins' table\n";

    // 3. Restructure 'contestants'
    $conn->exec("ALTER TABLE contestants 
        CHANGE contestant_id uuid VARCHAR(100),
        CHANGE cont_fname first_name VARCHAR(100),
        CHANGE cont_lname last_name VARCHAR(100),
        CHANGE cont_gender gender VARCHAR(10),
        CHANGE cont_position position_id VARCHAR(100),
        CHANGE cont_profile profile_image TEXT,
        CHANGE del_cont is_deleted ENUM('no', 'yes') DEFAULT 'no'");
    echo "Restructured 'contestants' table\n";

    // 4. Restructure 'results'
    $conn->exec("ALTER TABLE results 
        CHANGE vote_count_id uuid VARCHAR(100),
        CHANGE results votes_for INT(11) DEFAULT 0,
        CHANGE results_no votes_against INT(11) DEFAULT 0");
    echo "Restructured 'results' table\n";

    // 5. Restructure 'voter_participation'
    $conn->exec("ALTER TABLE voter_participation 
        CHANGE vhd_id uuid VARCHAR(100),
        CHANGE voterhasdone_time voted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        CHANGE voterhasdone_status status TINYINT(1) DEFAULT 1");
    echo "Restructured 'voter_participation' table\n";

    // 6. Restructure 'voter_security_logs'
    $conn->exec("ALTER TABLE voter_security_logs 
        CHANGE voter_login_details_id uuid VARCHAR(100),
        CHANGE details_location location VARCHAR(500),
        CHANGE voter_login_datetime login_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        CHANGE voter_logout_datetime logout_at DATETIME,
        CHANGE voter_login_details_status status TINYINT(1) DEFAULT 1");
    echo "Restructured 'voter_security_logs' table\n";

    // 7. Restructure 'activity_logs'
    $conn->exec("ALTER TABLE activity_logs 
        CHANGE log_id uuid VARCHAR(100),
        CHANGE log_message action TEXT,
        CHANGE log_person person_id VARCHAR(300),
        CHANGE createdAt created_at DATETIME DEFAULT CURRENT_TIMESTAMP");
    echo "Restructured 'activity_logs' table\n";

    echo "\nGLOBAL DATABASE RESTRUCTURE COMPLETE!\n";

} catch (PDOException $e) {
    echo "CRITICAL ERROR during migration: " . $e->getMessage() . "\n";
}
