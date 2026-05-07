<?php
require 'connection/conn.php';
$stmt = $conn->query("DESCRIBE admins");
$columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo implode("\n", $columns);
