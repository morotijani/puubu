<?php
require 'connection/conn.php';
$stmt = $conn->query("DESCRIBE voter_security_logs");
$columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo implode("\n", $columns);
