<?php
require 'connection/conn.php';
echo "TABLES:\n";
$stmt = $conn->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
print_r($tables);

foreach ($tables as $table) {
    echo "\nSCHEMA FOR $table:\n";
    $stmt = $conn->query("DESCRIBE `$table` ");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
}
