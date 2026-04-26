<?php
require 'connection/conn.php';
global $conn;
try {
    $conn->exec("ALTER TABLE positions ADD COLUMN gender_restriction VARCHAR(20) DEFAULT 'all' AFTER position_name");
    echo "Migration successful: gender_restriction added to positions table.\n";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
