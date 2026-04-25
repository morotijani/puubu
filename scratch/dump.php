<?php
require __DIR__  . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$conn = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);

$tables = ['puubu_admin', 'election', 'registrars', 'cont_details', 'positions', 'vote_counts'];
foreach ($tables as $table) {
    echo "\n=== $table ===\n";
    $stmt = $conn->query("DESCRIBE $table");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
}
