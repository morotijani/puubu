<?php
require 'connection/conn.php';
global $conn;
$stmt = $conn->query("DESCRIBE registrars");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
