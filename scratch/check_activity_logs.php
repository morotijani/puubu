<?php
require 'connection/conn.php';
echo "\n--- activity_logs ---\n";
$stmt = $conn->query("DESCRIBE activity_logs");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
