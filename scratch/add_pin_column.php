<?php
require_once __DIR__ . '/../connection/conn.php';
try {
    $conn->exec("ALTER TABLE voters ADD COLUMN pin_code VARCHAR(100) AFTER password");
    echo "Column 'pin_code' added successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
