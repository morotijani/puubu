<?php 

	// DATABASE CONNECTION
	require_once("../../connection/conn.php");

    $query = "
        SELECT id, log_message FROM puubu_logs 
        WHERE log_seen = 0 
        ORDER BY id DESC
    ";
    $statement = $conn->prepare($query);
    $result = $statement->execute();
    $query_count = $statement->rowCount();
    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

    if ($query_count > 0) {
        if ($rows) {
            $ids = array_column($rows, 'id');
            $in  = str_repeat('?,', count($ids) - 1) . '?';
            $update = $conn->prepare("UPDATE puubu_logs SET log_seen = 1 WHERE id IN ($in)");
            $update->execute($ids);
        }
        echo json_encode($rows);
	}
