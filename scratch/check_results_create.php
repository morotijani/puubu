<?php
require 'connection/conn.php';
$stmt = $conn->query("SHOW CREATE TABLE results");
echo $stmt->fetchColumn(1);
