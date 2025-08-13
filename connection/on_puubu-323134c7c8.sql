-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: sdb-x.hosting.stackcp.net
-- Generation Time: Oct 09, 2022 at 08:19 PM
-- Server version: 10.4.25-MariaDB-log
-- PHP Version: 7.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `on_puubu-323134c7c8`
--

-- --------------------------------------------------------

--
-- Table structure for table `cont_details`
--

CREATE TABLE `cont_details` (
  `cont_id` int(11) NOT NULL,
  `cont_indentification` varchar(10) NOT NULL,
  `cont_fname` varchar(100) NOT NULL,
  `cont_lname` varchar(100) NOT NULL,
  `cont_gender` varchar(10) NOT NULL,
  `cont_position` varchar(500) NOT NULL,
  `election_name` int(11) NOT NULL,
  `cont_profile` text NOT NULL,
  `del_cont` enum('no','yes') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `election`
--

CREATE TABLE `election` (
  `eid` int(11) NOT NULL,
  `election_name` varchar(225) NOT NULL,
  `election_by` varchar(255) NOT NULL,
  `added_date` datetime NOT NULL DEFAULT current_timestamp(),
  `stop_timer` timestamp NULL DEFAULT NULL,
  `election_manual_stop_time` datetime DEFAULT NULL,
  `session` tinyint(4) NOT NULL,
  `etrash` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `position_id` int(11) NOT NULL,
  `position_name` varchar(1000) NOT NULL,
  `election_id` int(11) NOT NULL,
  `position_skipped_votes` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `puubu_admin`
--

CREATE TABLE `puubu_admin` (
  `c_aid` int(11) NOT NULL,
  `cfname` varchar(100) NOT NULL,
  `clname` varchar(100) NOT NULL,
  `cemail` varchar(100) NOT NULL,
  `ckey` varchar(100) NOT NULL,
  `joined_date` datetime NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `trash` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `puubu_admin`
--

INSERT INTO `puubu_admin` (`c_aid`, `cfname`, `clname`, `cemail`, `ckey`, `joined_date`, `last_login`, `trash`) VALUES
(1, 'mohammed', 'inuwa', 'inuwa@puubu.com', '$2y$10$RSgpoOFGTDy7uR.fLI4djuhvIC0iYlnY4RmFlSI9348WDAkh8UuPq', '2020-02-21 21:01:31', '2022-08-21 22:37:53', 0);

-- --------------------------------------------------------

--
-- Table structure for table `puubu_election_logs`
--

CREATE TABLE `puubu_election_logs` (
  `puubu_election_logs_id` int(11) NOT NULL,
  `election_logs_election_id` int(11) DEFAULT NULL,
  `puubu_election_logs_datetime` datetime DEFAULT current_timestamp(),
  `election_logs_description` text NOT NULL,
  `election_logs_page` varchar(500) DEFAULT NULL,
  `election_logs_referrer` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `registrars`
--

CREATE TABLE `registrars` (
  `id` int(11) NOT NULL,
  `std_id` varchar(12) NOT NULL,
  `std_password` varchar(30) NOT NULL,
  `std_fname` varchar(30) NOT NULL,
  `std_lname` varchar(30) NOT NULL,
  `std_email` varchar(200) NOT NULL,
  `vote` enum('no','yes') NOT NULL,
  `election_type` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `voted_for`
--

CREATE TABLE `voted_for` (
  `id` int(11) NOT NULL,
  `voter_id` int(11) NOT NULL,
  `election_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `voted_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `voted_location` varchar(255) NOT NULL,
  `voted_ip` varchar(100) NOT NULL,
  `trash` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `voterhasdone`
--

CREATE TABLE `voterhasdone` (
  `vhd_id` int(11) NOT NULL,
  `voter_id` int(11) NOT NULL,
  `election_id` int(11) NOT NULL,
  `voterhasdone_time` datetime DEFAULT current_timestamp(),
  `voterhasdone_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `voter_login_details`
--

CREATE TABLE `voter_login_details` (
  `voter_login_details_id` int(11) NOT NULL,
  `voter_id` int(11) NOT NULL,
  `voter_login_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `voter_logout_datetime` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `voter_login_details_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `vote_counts`
--

CREATE TABLE `vote_counts` (
  `id` int(11) NOT NULL,
  `results` int(11) NOT NULL,
  `results_no` int(11) NOT NULL,
  `cont_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `election_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cont_details`
--
ALTER TABLE `cont_details`
  ADD PRIMARY KEY (`cont_id`);

--
-- Indexes for table `election`
--
ALTER TABLE `election`
  ADD PRIMARY KEY (`eid`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`position_id`);

--
-- Indexes for table `puubu_admin`
--
ALTER TABLE `puubu_admin`
  ADD PRIMARY KEY (`c_aid`);

--
-- Indexes for table `puubu_election_logs`
--
ALTER TABLE `puubu_election_logs`
  ADD PRIMARY KEY (`puubu_election_logs_id`);

--
-- Indexes for table `registrars`
--
ALTER TABLE `registrars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `election_type` (`election_type`);

--
-- Indexes for table `voted_for`
--
ALTER TABLE `voted_for`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voter_id` (`voter_id`),
  ADD KEY `election_id` (`election_id`),
  ADD KEY `position_id` (`position_id`),
  ADD KEY `candidate_id` (`candidate_id`);

--
-- Indexes for table `voterhasdone`
--
ALTER TABLE `voterhasdone`
  ADD PRIMARY KEY (`vhd_id`);

--
-- Indexes for table `voter_login_details`
--
ALTER TABLE `voter_login_details`
  ADD PRIMARY KEY (`voter_login_details_id`);

--
-- Indexes for table `vote_counts`
--
ALTER TABLE `vote_counts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cont_details`
--
ALTER TABLE `cont_details`
  MODIFY `cont_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `election`
--
ALTER TABLE `election`
  MODIFY `eid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `puubu_admin`
--
ALTER TABLE `puubu_admin`
  MODIFY `c_aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `puubu_election_logs`
--
ALTER TABLE `puubu_election_logs`
  MODIFY `puubu_election_logs_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registrars`
--
ALTER TABLE `registrars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voted_for`
--
ALTER TABLE `voted_for`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voterhasdone`
--
ALTER TABLE `voterhasdone`
  MODIFY `vhd_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voter_login_details`
--
ALTER TABLE `voter_login_details`
  MODIFY `voter_login_details_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vote_counts`
--
ALTER TABLE `vote_counts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
