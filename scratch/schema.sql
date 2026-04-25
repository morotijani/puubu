
CREATE TABLE `puubu_admin` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `admin_id` varchar(100) DEFAULT NULL,
  `cfname` varchar(100) NOT NULL,
  `clname` varchar(100) NOT NULL,
  `cemail` varchar(100) NOT NULL,
  `ckey` varchar(100) NOT NULL,
  `joined_date` datetime NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `trash` tinyint(4) NOT NULL,
  `google_auth_secret` varchar(100) DEFAULT NULL,
  `is_2fa_enabled` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `election` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `election_id` varchar(100) DEFAULT NULL,
  `election_name` varchar(225) NOT NULL,
  `election_by` varchar(255) NOT NULL,
  `added_date` datetime NOT NULL DEFAULT current_timestamp(),
  `stop_timer` timestamp NULL DEFAULT NULL,
  `election_manual_stop_time` datetime DEFAULT NULL,
  `session` tinyint(4) NOT NULL,
  `etrash` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `registrars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voter_id` varchar(100) DEFAULT NULL,
  `std_id` varchar(12) NOT NULL,
  `std_password` varchar(225) NOT NULL,
  `std_fname` varchar(30) NOT NULL,
  `std_lname` varchar(30) NOT NULL,
  `std_email` varchar(200) NOT NULL,
  `vote` enum('no','yes') NOT NULL,
  `registrar_election` varchar(100) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `election_type` (`registrar_election`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `cont_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `contestant_id` varchar(100) DEFAULT NULL,
  `contestant_ballot_number` varchar(10) DEFAULT NULL,
  `cont_fname` varchar(100) NOT NULL,
  `cont_lname` varchar(100) NOT NULL,
  `cont_gender` varchar(10) NOT NULL,
  `cont_position` varchar(100) NOT NULL,
  `contestant_election` varchar(100) NOT NULL,
  `cont_profile` text NOT NULL,
  `del_cont` enum('no','yes') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `positions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `position_id` varchar(100) DEFAULT NULL,
  `position_name` varchar(1000) NOT NULL,
  `election_id` varchar(100) DEFAULT NULL,
  `position_skipped_votes` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `vote_counts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `vote_count_id` varchar(100) DEFAULT NULL,
  `results` int(11) NOT NULL,
  `results_no` int(11) NOT NULL,
  `contestant_id` varchar(100) DEFAULT NULL,
  `position_id` varchar(100) DEFAULT NULL,
  `election_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `voterhasdone` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `vhd_id` varchar(100) DEFAULT NULL,
  `voter_id` varchar(100) DEFAULT NULL,
  `election_id` varchar(100) DEFAULT NULL,
  `voterhasdone_time` datetime DEFAULT current_timestamp(),
  `voterhasdone_status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
