<?php
require __DIR__  . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$conn = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $conn->exec("SET FOREIGN_KEY_CHECKS = 0;");

    // 1. Wipe existing dummy data (Except admin, maybe we keep the main admin)
    // Actually, let's keep puubu_admin, but update it.
    // We will wipe the others to make FKs work cleanly.
    $tablesToTruncate = ['election', 'registrars', 'cont_details', 'positions', 'vote_counts', 'voterhasdone'];
    foreach ($tablesToTruncate as $table) {
        $conn->exec("TRUNCATE TABLE `$table`");
        echo "Truncated $table\n";
    }

    // 2. Alter puubu_admin
    $conn->exec("ALTER TABLE `puubu_admin` 
                 ADD COLUMN `role` ENUM('super_admin', 'organizer') NOT NULL DEFAULT 'organizer' AFTER `admin_id`");
    // Set existing admin to super_admin
    $conn->exec("UPDATE `puubu_admin` SET `role` = 'super_admin'");
    echo "Altered puubu_admin\n";

    // 3. Alter election table
    // Must add organizer_id
    $conn->exec("ALTER TABLE `election` 
                 ADD COLUMN `organizer_id` VARCHAR(100) NULL AFTER `election_id`");
    
    // Convert to proper foreign key for organizer (linking to admin_id)
    // puubu_admin.admin_id is a varchar(100), we need an index on it first.
    // wait, puubu_admin primary key is `id`. `admin_id` is a unique string.
    // Let's add a unique index on admin_id just in case, though it might exist.
    try {
        $conn->exec("ALTER TABLE `puubu_admin` ADD UNIQUE INDEX `idx_admin_id` (`admin_id`)");
    } catch(Exception $e) {}

    $conn->exec("ALTER TABLE `election` ADD CONSTRAINT `fk_election_organizer` FOREIGN KEY (`organizer_id`) REFERENCES `puubu_admin`(`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    echo "Altered election\n";

    // 4. Alter positions
    // Make election_id a foreign key
    try {
        $conn->exec("ALTER TABLE `election` ADD UNIQUE INDEX `idx_election_id` (`election_id`)");
    } catch(Exception $e) {}

    $conn->exec("ALTER TABLE `positions` 
                 ADD CONSTRAINT `fk_positions_election` FOREIGN KEY (`election_id`) REFERENCES `election`(`election_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    echo "Altered positions\n";

    // 5. Alter cont_details
    // Make contestant_election a foreign key linking to election_id
    $conn->exec("ALTER TABLE `cont_details` 
                 ADD CONSTRAINT `fk_cont_election` FOREIGN KEY (`contestant_election`) REFERENCES `election`(`election_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    
    try {
        $conn->exec("ALTER TABLE `positions` ADD UNIQUE INDEX `idx_position_id` (`position_id`)");
    } catch(Exception $e) {}
    
    // contestant also links to position
    $conn->exec("ALTER TABLE `cont_details`
                 ADD CONSTRAINT `fk_cont_position` FOREIGN KEY (`cont_position`) REFERENCES `positions`(`position_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    echo "Altered cont_details\n";

    // 6. Alter registrars
    $conn->exec("ALTER TABLE `registrars` 
                 ADD CONSTRAINT `fk_voter_election` FOREIGN KEY (`registrar_election`) REFERENCES `election`(`election_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    echo "Altered registrars\n";

    // 7. Alter vote_counts
    // Needs contestant_id, position_id, election_id FKs
    try {
        $conn->exec("ALTER TABLE `cont_details` ADD UNIQUE INDEX `idx_contestant_id` (`contestant_id`)");
    } catch(Exception $e) {}

    $conn->exec("ALTER TABLE `vote_counts`
                 ADD CONSTRAINT `fk_votecount_election` FOREIGN KEY (`election_id`) REFERENCES `election`(`election_id`) ON DELETE CASCADE ON UPDATE CASCADE,
                 ADD CONSTRAINT `fk_votecount_position` FOREIGN KEY (`position_id`) REFERENCES `positions`(`position_id`) ON DELETE CASCADE ON UPDATE CASCADE,
                 ADD CONSTRAINT `fk_votecount_contestant` FOREIGN KEY (`contestant_id`) REFERENCES `cont_details`(`contestant_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    echo "Altered vote_counts\n";

    // 8. Alter voterhasdone
    try {
        $conn->exec("ALTER TABLE `registrars` ADD UNIQUE INDEX `idx_voter_id` (`voter_id`)");
    } catch(Exception $e) {}

    $conn->exec("ALTER TABLE `voterhasdone`
                 ADD CONSTRAINT `fk_hasdone_election` FOREIGN KEY (`election_id`) REFERENCES `election`(`election_id`) ON DELETE CASCADE ON UPDATE CASCADE,
                 ADD CONSTRAINT `fk_hasdone_voter` FOREIGN KEY (`voter_id`) REFERENCES `registrars`(`voter_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    echo "Altered voterhasdone\n";

    $conn->exec("SET FOREIGN_KEY_CHECKS = 1;");
    echo "Migration completed successfully.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
