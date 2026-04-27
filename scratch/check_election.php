<?php
require 'connection/conn.php';
$stmt = $conn->query("SELECT * FROM election WHERE session = 1");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
