<?php
require 'connection/conn.php';
$stmt = $conn->query("DESCRIBE election");
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}
