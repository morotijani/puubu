<?php
$conn = new PDO('mysql:host=localhost;dbname=puubu', 'root', '');
$stmt = $conn->query("SHOW COLUMNS FROM activity_logs");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
