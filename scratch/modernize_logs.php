<?php
try {
    $conn = new PDO('mysql:host=localhost;dbname=puubu', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Modernize activity_logs table columns
    $cols = $conn->query("SHOW COLUMNS FROM activity_logs")->fetchAll(PDO::FETCH_COLUMN);
    
    // uuid (keeping it as unique log entry identifier if it exists, or for backward compatibility)
    // action (renaming to log_message)
    if (in_array('action', $cols)) {
        $conn->exec("ALTER TABLE activity_logs CHANGE action log_message TEXT");
        echo "Changed action to log_message\n";
    }
    
    // person_id (renaming to user_uuid)
    if (in_array('person_id', $cols)) {
        $conn->exec("ALTER TABLE activity_logs CHANGE person_id user_uuid VARCHAR(100)");
        echo "Changed person_id to user_uuid\n";
    }
    
    // log_id (adding if it doesn't exist, as some parts of the code use it)
    if (!in_array('log_id', $cols)) {
        $conn->exec("ALTER TABLE activity_logs ADD log_id VARCHAR(100) AFTER id");
        echo "Added log_id\n";
    }

    echo "Activity logs modernization complete\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
