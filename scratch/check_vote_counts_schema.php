<?php
require 'connection/conn.php';
global $conn;
$stmt = $conn->query("DESCRIBE vote_counts");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
