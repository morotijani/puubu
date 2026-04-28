<?php
require 'connection/conn.php';
$stmt = $conn->query("DESCRIBE positions");
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo $row['Field'] . "\n";
}
