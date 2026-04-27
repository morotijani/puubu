<?php
require 'connection/conn.php';
$stmt = $conn->prepare("UPDATE election SET stop_timer = end_date WHERE session = 1 AND (stop_timer IS NULL OR stop_timer = '')");
$stmt->execute();
echo "Updated " . $stmt->rowCount() . " rows.";
