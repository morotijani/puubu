<?php
require_once __DIR__ . '/../bootstrap.php';
global $conn;
$stmt = $conn->query("DESCRIBE positions");
$schema = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($schema);
