<?php
require 'connection/conn.php';
$stmt = $conn->query("DESCRIBE election");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
