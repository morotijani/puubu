<?php
require 'connection/conn.php';
global $conn;
try {
    $conn->exec("ALTER TABLE registrars ADD COLUMN std_gender ENUM('male', 'female') DEFAULT 'male' AFTER std_lname");
    echo "Migration successful: std_gender added to registrars table.\n";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
