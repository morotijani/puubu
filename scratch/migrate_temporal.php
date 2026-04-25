<?php
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$conn = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $conn->exec("ALTER TABLE election ADD COLUMN start_date DATETIME NULL AFTER added_date");
    echo "Added start_date column.\n";
} catch (Exception $e) {
    echo "start_date column already exists or error: " . $e->getMessage() . "\n";
}

try {
    $conn->exec("ALTER TABLE election ADD COLUMN end_date DATETIME NULL AFTER start_date");
    echo "Added end_date column.\n";
} catch (Exception $e) {
    echo "end_date column already exists or error: " . $e->getMessage() . "\n";
}

try {
    $conn->exec("ALTER TABLE election ADD COLUMN manual_stop_reason TEXT NULL AFTER end_date");
    echo "Added manual_stop_reason column.\n";
} catch (Exception $e) {
    echo "manual_stop_reason column already exists or error: " . $e->getMessage() . "\n";
}
