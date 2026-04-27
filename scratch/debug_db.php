<?php
require 'connection/conn.php';
$stmt = $conn->query("DESCRIBE election");
echo "<pre>";
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
echo "</pre>";

$stmt = $conn->query("SELECT * FROM election LIMIT 1");
echo "<pre>";
print_r($stmt->fetch(PDO::FETCH_ASSOC));
echo "</pre>";
