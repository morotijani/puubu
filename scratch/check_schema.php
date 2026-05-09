<?php
require_once __DIR__ . '/../connection/conn.php';
$stmt = $conn->query("SHOW COLUMNS FROM voters");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
