<?php
try {
    $conn = new PDO('mysql:host=localhost;dbname=puubu', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 1. Rename table if old one exists and new one doesn't
    $stmt = $conn->query("SHOW TABLES LIKE 'puubu_logs'");
    $oldTableExists = $stmt->rowCount() > 0;
    $stmt = $conn->query("SHOW TABLES LIKE 'activity_logs'");
    $newTableExists = $stmt->rowCount() > 0;
    
    if ($oldTableExists && !$newTableExists) {
        $conn->exec("RENAME TABLE puubu_logs TO activity_logs");
        echo "Table puubu_logs renamed to activity_logs\n";
    } elseif (!$oldTableExists && !$newTableExists) {
        // Create it if neither exists
        $conn->exec("CREATE TABLE activity_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            log_id VARCHAR(100),
            log_message TEXT,
            user_uuid VARCHAR(100),
            log_type VARCHAR(50),
            createdAt DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        echo "Table activity_logs created\n";
    }
    
    // Refresh connection to ensure we are working with the latest state
    $stmt = $conn->query("SHOW TABLES LIKE 'activity_logs'");
    if ($stmt->rowCount() > 0) {
        // 2. Add new column user_uuid if it doesn't exist
        $stmt = $conn->query("SHOW COLUMNS FROM activity_logs LIKE 'user_uuid'");
        if ($stmt->rowCount() == 0) {
            // Find old column log_person
            $stmt = $conn->query("SHOW COLUMNS FROM activity_logs LIKE 'log_person'");
            if ($stmt->rowCount() > 0) {
                $conn->exec("ALTER TABLE activity_logs CHANGE log_person user_uuid VARCHAR(100)");
                echo "Column log_person changed to user_uuid\n";
            } else {
                $conn->exec("ALTER TABLE activity_logs ADD user_uuid VARCHAR(100) AFTER log_message");
                echo "Column user_uuid added\n";
            }
        }
    }
    
    echo "Database sync complete\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
