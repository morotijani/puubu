<?php
require 'connection/conn.php';
echo "\n--- voted_for ---\n";
$stmt = $conn->query("DESCRIBE voted_for");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
