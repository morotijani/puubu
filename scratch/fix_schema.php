<?php
try {
    $conn = new PDO('mysql:host=localhost;dbname=Kokuromotie', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if column exists
    $stmt = $conn->query("DESCRIBE positions");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Columns: " . implode(", ", $columns) . "\n";
    
    if (!in_array('gender_restriction', $columns)) {
        echo "Adding gender_restriction column...\n";
        $conn->exec("ALTER TABLE positions ADD COLUMN gender_restriction ENUM('all', 'male', 'female') DEFAULT 'all' AFTER election_uuid");
        echo "Column added successfully.\n";
    } else {
        echo "Column gender_restriction already exists.\n";
    }

    // Check election table
    $stmt = $conn->query("DESCRIBE election");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Election Columns: " . implode(", ", $columns) . "\n";

    if (!in_array('starts_at', $columns)) {
        echo "Adding starts_at column...\n";
        $conn->exec("ALTER TABLE election ADD COLUMN starts_at DATETIME AFTER organized_by");
    }
    if (!in_array('ends_at', $columns)) {
        echo "Adding ends_at column...\n";
        $conn->exec("ALTER TABLE election ADD COLUMN ends_at DATETIME AFTER starts_at");
    }
    
    echo "Schema check complete.\n";

    // Create activity_logs table if not exists
    echo "Checking activity_logs table...\n";
    $conn->exec("
        CREATE TABLE IF NOT EXISTS `activity_logs` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uuid` varchar(255) NOT NULL,
            `log_message` text NOT NULL,
            `user_uuid` varchar(255) NOT NULL,
            `log_type` varchar(50) NOT NULL,
            `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "activity_logs table checked/created.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
