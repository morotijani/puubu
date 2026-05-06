<?php
require 'connection/conn.php';

try {
    // 1. Check if both columns exist
    $stmt = $conn->query("DESCRIBE activity_logs");
    $cols = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'Field');

    if (in_array('log_id', $cols) && in_array('uuid', $cols)) {
        echo "Syncing log_id values to uuid where uuid is empty...\n";
        $conn->exec("UPDATE activity_logs SET uuid = log_id WHERE uuid IS NULL OR uuid = ''");
        
        echo "Dropping log_id column...\n";
        $conn->exec("ALTER TABLE activity_logs DROP COLUMN log_id");
        echo "Success: log_id removed and data preserved in uuid.\n";
    } elseif (in_array('log_id', $cols) && !in_array('uuid', $cols)) {
        echo "Renaming log_id to uuid...\n";
        $conn->exec("ALTER TABLE activity_logs CHANGE log_id uuid VARCHAR(100)");
        echo "Success: log_id renamed to uuid.\n";
    } else {
        echo "No log_id column found or schema already modernized.\n";
    }

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
