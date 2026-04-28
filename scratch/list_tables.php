<?php
$conn = new PDO('mysql:host=localhost;dbname=puubu', 'root', '');
$stmt = $conn->query("SHOW TABLES");
print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
