<?php
require_once __DIR__ . '/../connection/conn.php';
global $conn;

try {
    $stmt = $conn->query("ALTER TABLE positions ADD COLUMN display_order INT DEFAULT 0 AFTER position_name");
    echo "Database altered successfully.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Column already exists.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
