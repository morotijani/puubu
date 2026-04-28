<?php
require 'connection/conn.php';
$stmt = $conn->query("DESCRIBE registrars");
echo "<pre>";
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
echo "</pre>";
