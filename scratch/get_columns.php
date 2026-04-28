<?php
require 'connection/conn.php';
$tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
foreach($tables as $table) {
    echo "--- Table: $table ---\n";
    $stmt = $conn->query("DESCRIBE `$table` ");
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        echo "  " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
    echo "\n";
}
