<?php
require 'connection/conn.php';
$stmt = $conn->query("DESCRIBE registrars");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
