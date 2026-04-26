<?php
require 'connection/conn.php';
global $conn;
$stmt = $conn->query("DESCRIBE puubu_admin");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
