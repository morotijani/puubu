<?php
require __DIR__  . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$conn = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
$tables = ['puubu_admin', 'election', 'registrars', 'cont_details', 'positions', 'vote_counts', 'voterhasdone'];
$out = "";
foreach ($tables as $table) {
    $stmt = $conn->query("SHOW CREATE TABLE $table");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $out .= "\n" . $row['Create Table'] . ";\n";
}
file_put_contents(__DIR__ . '/schema.sql', $out);
