-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2025 at 08:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `puubu`
--

-- --------------------------------------------------------

--
-- Table structure for table `cont_details`
--

CREATE TABLE `cont_details` (
  `id` bigint(20) NOT NULL,
  `contestant_id` varchar(100) DEFAULT NULL,
  `contestant_ballot_number` varchar(10) DEFAULT NULL,
  `cont_fname` varchar(100) NOT NULL,
  `cont_lname` varchar(100) NOT NULL,
  `cont_gender` varchar(10) NOT NULL,
  `cont_position` varchar(100) NOT NULL,
  `contestant_election` varchar(100) NOT NULL,
  `cont_profile` text NOT NULL,
  `del_cont` enum('no','yes') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cont_details`
--

INSERT INTO `cont_details` (`id`, `contestant_id`, `contestant_ballot_number`, `cont_fname`, `cont_lname`, `cont_gender`, `cont_position`, `contestant_election`, `cont_profile`, `del_cont`) VALUES
(1, '96ffeb2e-5c09-48eb-93ab-93e554de26ce', '1', 'Hassan', 'Ali', 'male', '075e212d-2f94-4b3b-94ea-02e6d6e57f49', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '68c907b3042774.56524196.jpg', 'no'),
(2, '502db295-da7c-4bd3-aa13-082b1800dc5a', '2', 'Abena', 'Osei', 'female', '075e212d-2f94-4b3b-94ea-02e6d6e57f49', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '68c907d088dc15.66577302.jpg', 'no'),
(3, '5814420d-f91f-40dd-baf9-148ff22e9a66', '1', 'Hamza', 'Hussein', 'male', '51ef56ad-11a9-4666-b964-2d30e9006eef', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '68c9081b75ca86.86927732.jpg', 'no'),
(4, 'd47d7ac6-b696-4801-a749-721a22f225f7', '1', 'Awudu', 'Yakub', 'male', '7af546ef-4d89-48af-a17c-c2bc87930541', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '68c9083ab3a103.46497665.jpg', 'no'),
(5, 'a09a31cc-93b5-454c-8c0e-22599e906bd6', '2', 'Afia', 'Konadu', 'female', '7af546ef-4d89-48af-a17c-c2bc87930541', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '68c9087808eab9.38001699.jpg', 'no'),
(6, '8bcb91f5-1ade-4fa5-ac44-09481ace7751', '2', 'Henry', 'Asamoah', 'male', 'dcf6361c-7cbe-4775-ab35-9a3fffae310a', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '68c908a20c0cd4.67192464.jpg', 'no'),
(7, 'bba912ed-1f4a-4fa7-b550-5fc3bd63ba2b', '1', 'Gloria', 'Afriyie', 'female', 'dcf6361c-7cbe-4775-ab35-9a3fffae310a', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '68c908ffd741b1.34419941.jpg', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `election`
--

CREATE TABLE `election` (
  `id` bigint(20) NOT NULL,
  `election_id` varchar(100) DEFAULT NULL,
  `election_name` varchar(225) NOT NULL,
  `election_by` varchar(255) NOT NULL,
  `added_date` datetime NOT NULL DEFAULT current_timestamp(),
  `stop_timer` timestamp NULL DEFAULT NULL,
  `election_manual_stop_time` datetime DEFAULT NULL,
  `session` tinyint(4) NOT NULL,
  `etrash` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `election`
--

INSERT INTO `election` (`id`, `election_id`, `election_name`, `election_by`, `added_date`, `stop_timer`, `election_manual_stop_time`, `session`, `etrash`) VALUES
(1, '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 'Nutrition Department', 'TF Company.', '2025-09-16 06:36:17', '2025-09-16 08:43:00', '2025-09-16 12:24:45', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` bigint(20) NOT NULL,
  `position_id` varchar(100) DEFAULT NULL,
  `position_name` varchar(1000) NOT NULL,
  `election_id` varchar(100) DEFAULT NULL,
  `position_skipped_votes` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `position_id`, `position_name`, `election_id`, `position_skipped_votes`) VALUES
(1, '075e212d-2f94-4b3b-94ea-02e6d6e57f49', 'President', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 0),
(2, 'dcf6361c-7cbe-4775-ab35-9a3fffae310a', 'PRO.', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 1),
(3, '7af546ef-4d89-48af-a17c-c2bc87930541', 'Organizer', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 0),
(4, '51ef56ad-11a9-4666-b964-2d30e9006eef', 'COO', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 3);

-- --------------------------------------------------------

--
-- Table structure for table `puubu_admin`
--

CREATE TABLE `puubu_admin` (
  `id` bigint(20) NOT NULL,
  `admin_id` varchar(100) DEFAULT NULL,
  `cfname` varchar(100) NOT NULL,
  `clname` varchar(100) NOT NULL,
  `cemail` varchar(100) NOT NULL,
  `ckey` varchar(100) NOT NULL,
  `joined_date` datetime NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `trash` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `puubu_admin`
--

INSERT INTO `puubu_admin` (`id`, `admin_id`, `cfname`, `clname`, `cemail`, `ckey`, `joined_date`, `last_login`, `trash`) VALUES
(1, 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'mohammed', 'inuwas', 'inuwa@puubu.com', '$2y$10$w7Ka7PeLjrctcyXL1G1.XOFvdGa9AVDt7SfzZLS27efg/E6l33GN2', '2020-02-21 21:01:31', '2025-09-16 18:50:21', 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `puubu_logs`
--

CREATE TABLE `puubu_logs` (
  `id` bigint(20) NOT NULL,
  `log_id` varchar(300) DEFAULT NULL,
  `log_message` text DEFAULT NULL,
  `log_person` varchar(300) DEFAULT NULL,
  `log_type` enum('admin','user') DEFAULT NULL,
  `log_seen` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `log_status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `puubu_logs`
--

INSERT INTO `puubu_logs` (`id`, `log_id`, `log_message`, `log_person`, `log_type`, `log_seen`, `createdAt`, `updatedAt`, `log_status`) VALUES
(1, '351495cf-bfdd-4aa8-9c93-422fc2c1e406', 'logged into the system', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 06:34:08', '2025-09-16 18:38:18', 0),
(2, 'c54fc628-0bab-49f3-ba41-9d4c27560fc9', 'new election added!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 06:36:17', '2025-09-16 18:41:36', 0),
(3, 'dd3a396a-c921-4dda-b2ad-000bfe0ce1c3', 'updated election [\'9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5\']!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 06:39:32', '2025-09-16 18:42:00', 0),
(4, '9ffd5e9d-464e-4de9-a26b-aadde71c8370', 'updated election [\'9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5\']!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 06:39:45', '2025-09-16 18:37:48', 0),
(5, '37fe40ea-7183-4679-9206-015a5e763990', 'new position added!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 06:40:15', '2025-09-16 18:42:55', 0),
(6, '55b79192-51bc-4c67-9bc4-2e70703f3c5f', 'new position added!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 06:40:22', '2025-09-16 18:37:48', 0),
(7, 'c19786ba-95f8-4f04-a7ed-c7cf22fe1f0a', 'new position added!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 06:40:49', '2025-09-16 18:43:10', 0),
(8, '3ec72db5-39f0-4dbb-89fa-b192de94558a', 'new position added!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 06:41:13', '2025-09-16 18:45:10', 0),
(9, '48b56516-8ae4-46e2-8940-171e87d2babd', 'position [\'dcf6361c-7cbe-4775-ab35-9a3fffae310a\'], updated!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 06:43:20', '2025-09-16 18:37:48', 0),
(10, '235b9121-db24-4e87-af93-f526e0e36477', 'new contestant, added!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 06:46:11', '2025-09-16 18:37:48', 0),
(11, 'a57af24c-88be-4b24-9143-c8cd8dcc5875', 'new contestant, added!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 06:46:40', '2025-09-16 18:37:48', 0),
(12, '7ae1d134-9170-4561-97bb-5caf55631993', 'new contestant, added!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 06:47:55', '2025-09-16 18:37:48', 0),
(13, '16a1850c-f587-4523-aad5-e6b40ac08e28', 'new contestant, added!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 06:48:26', '2025-09-16 18:37:48', 0),
(14, 'a9f10bae-b7d8-42bf-a670-efe07e33ae08', 'new contestant, added!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 06:49:28', '2025-09-16 18:37:48', 0),
(15, '877f91e4-bcd9-4a13-873b-2f1520584c80', 'new contestant, added!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 06:50:10', '2025-09-16 18:37:48', 0),
(16, 'e86f10a6-1f38-4739-a99c-b98f188a8f05', 'new contestant, added!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 06:51:43', '2025-09-16 18:37:48', 0),
(17, '897fd748-3353-4f8b-97c0-187967411ff7', 'contestant [\'502db295-da7c-4bd3-aa13-082b1800dc5a\'], updated!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 06:58:12', '2025-09-16 18:37:48', 0),
(18, '8f1ef51b-279b-4ec5-9838-594ef16e8d18', '1 new voter(s), added!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 07:09:38', '2025-09-16 18:37:48', 0),
(19, 'ff892cda-8aab-45ee-a2dd-c0dfe9efcefb', '2 new voter(s), added!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 07:10:47', '2025-09-16 18:37:48', 0),
(20, '6b7139dd-64b7-4ff5-b4aa-73e095f1944d', '2 new voter(s), added!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 07:18:40', '2025-09-16 18:37:48', 0),
(21, '7a97eaa1-4c50-484b-882a-7aa0407549eb', '1 new voter(s), added!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 07:23:05', '2025-09-16 18:37:48', 0),
(22, '957d525c-c9e4-43b4-9604-92a2cf038202', 'voter [\'Tijani Moro\'], loggedin!', NULL, 'user', 1, '2025-09-16 08:10:42', '2025-09-16 18:37:48', 0),
(23, 'cc1db146-4402-4017-9b9f-f82120f1c6b7', 'voter [\'Tijani Moro\'], loggedin!', NULL, 'user', 1, '2025-09-16 08:13:04', '2025-09-16 18:37:48', 0),
(24, 'ed5be885-39cc-4844-8ff7-1165642a52c2', 'voter [\'Tijani Moro\'], loggedin!', 'd02df51f-85c5-4c38-a8ba-d55c1c73e4e6', 'user', 1, '2025-09-16 09:24:07', '2025-09-16 18:37:48', 0),
(25, '00a335f8-ba53-4329-a302-17b6d1466c63', 'voter [\'Tijani Moro\'], has completed voting!', NULL, 'admin', 1, '2025-09-16 09:28:44', '2025-09-16 18:37:48', 0),
(26, '575bdd90-2374-48e5-b667-d1cef2d16b2e', 'voter [\'Tijani Moro\'], loggedin!', 'd02df51f-85c5-4c38-a8ba-d55c1c73e4e6', 'user', 1, '2025-09-16 09:30:32', '2025-09-16 18:37:48', 0),
(27, '953d09be-a3fb-4688-9b0c-ba00f0eaf8dc', 'voter [\'Ahmed Abass\'], loggedin!', 'd08df5sw-85c5-4c38-a8ba-d55c1c7708e6', 'user', 1, '2025-09-16 09:32:52', '2025-09-16 18:37:48', 0),
(28, '3d7360b7-716f-4395-bdbd-da27dbf861a9', 'voter [\'Ahmed Abass\'], has completed voting!', 'd08df5sw-85c5-4c38-a8ba-d55c1c7708e6', 'admin', 1, '2025-09-16 09:33:38', '2025-09-16 18:37:48', 0),
(29, 'b615b021-8126-406d-8b32-f5c741f1ff4b', 'voter [\'Ummi Kulsum\'], loggedin!', '3768fdc2-78a0-44b8-adaa-3efe17e25cfd', 'user', 1, '2025-09-16 09:35:24', '2025-09-16 18:37:48', 0),
(30, '616f6f6d-1ce4-4e06-a483-b8f3e8b9db95', 'voter [\'Ummi Kulsum\'], has completed voting!', '3768fdc2-78a0-44b8-adaa-3efe17e25cfd', 'admin', 1, '2025-09-16 09:35:53', '2025-09-16 18:37:48', 0),
(31, '311542fb-4a8d-40ef-9d34-1d4d4d0c037c', 'voter [\'Aban Papa\'], loggedin!', 'd02df51f-85c5-4c38-a8ba-d55c1c7708e6', 'user', 1, '2025-09-16 09:57:12', '2025-09-16 18:37:48', 0),
(32, '13fc1a15-3eb4-41df-8123-a06c567aee5e', 'voter [\'Aban Papa\'], has completed voting!', 'd02df51f-85c5-4c38-a8ba-d55c1c7708e6', 'admin', 1, '2025-09-16 10:04:07', '2025-09-16 18:37:48', 0),
(33, '1d76c3fa-c61e-4ad9-9c0c-cc47aa5ad623', 'voter [\'Alim Brother\'], loggedin!', 'dcb746d4-e837-45a7-8162-2e75863e78cf', 'user', 1, '2025-09-16 10:23:34', '2025-09-16 18:37:48', 0),
(34, '380ba6f7-3798-4999-a917-656e54bda631', 'voter [\'Alim Brother\'], has completed voting!', 'dcb746d4-e837-45a7-8162-2e75863e78cf', 'admin', 1, '2025-09-16 10:24:27', '2025-09-16 18:37:48', 0),
(35, '59cc634d-6f3e-437f-9aa2-e3de8f4c99c8', 'voter [\'Akwasi Desmond\'], loggedin!', 'bc4f3278-d621-4c44-9a87-aa664a5708ec', 'user', 1, '2025-09-16 10:26:41', '2025-09-16 18:37:48', 0),
(36, 'b2c52e2c-631b-43a6-acb8-84455f254380', 'voter [\'Akwasi Desmond\'], has completed voting!', 'bc4f3278-d621-4c44-9a87-aa664a5708ec', 'admin', 1, '2025-09-16 10:27:36', '2025-09-16 18:37:48', 0),
(37, '084fbacf-a587-45c4-91ee-5067646fb2d8', 'voter [\'Yahya Tahiru\'], loggedin!', '938a5c62-39b0-4c2c-a3b8-303234218ba0', 'user', 1, '2025-09-16 10:43:37', '2025-09-16 18:37:48', 0),
(38, 'bdd03f40-45ec-4e73-b198-a484c0cf5609', 'voter [\'Yahya Tahiru\'], has completed voting!', '938a5c62-39b0-4c2c-a3b8-303234218ba0', 'admin', 1, '2025-09-16 10:44:16', '2025-09-16 18:37:48', 0),
(39, 'a7c7184f-0118-4bf4-a2ab-7aaa025d24cc', 'voter [\'Madam Moro\'], loggedin, location (\'Kumasi, Ashanti, GH\')!', '8dfdf37a-e21b-4135-87f6-7e844d6b905e', 'user', 1, '2025-09-16 11:12:06', '2025-09-16 18:37:48', 0),
(40, 'a66f869a-92e0-4531-beff-8a31649d9f2b', 'voter [\'Madam Moro\'], has completed voting!', '8dfdf37a-e21b-4135-87f6-7e844d6b905e', 'admin', 1, '2025-09-16 11:56:20', '2025-09-16 18:37:48', 0),
(41, '6d9e9989-5cf5-4549-b9a7-120b1c57e858', 'voter [\'Kapo Ali\'], loggedin, location (\'Kumasi, Ashanti, GH154.161.245.238\')!', 'bc4f3278-DER1-4c44-9a87-aa664a5708ec', 'user', 1, '2025-09-16 12:02:41', '2025-09-16 18:37:48', 0),
(42, '11fda08b-d51b-4b82-9b66-c631d3e3dec7', 'voter [\'Kapo Ali\'], loggedin, location (\'Kumasi, Ashanti, GH154.161.245.238\')!', 'bc4f3278-DER1-4c44-9a87-aa664a5708ec', 'user', 1, '2025-09-16 12:05:52', '2025-09-16 18:37:48', 0),
(43, '7504e6eb-4854-4e0d-b0c9-bcab037f6ca8', 'voter [\'Kapo Ali\'], has completed voting!', 'bc4f3278-DER1-4c44-9a87-aa664a5708ec', 'admin', 1, '2025-09-16 12:22:46', '2025-09-16 18:37:48', 0),
(44, 'b5bdf569-f10f-4e95-b9a9-8dea63bc2227', 'search on voted for on position [\'PRO.\']!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 12:23:51', '2025-09-16 18:37:48', 0),
(45, '67cd1f0d-27d8-465c-9a14-0c64058bb98d', 'search on voted for on position [\'President\']!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 12:24:02', '2025-09-16 18:37:48', 0),
(46, '7c446c2c-6961-4380-bcfc-20e90fa9b5ea', 'search on voted for on position [\'Organizer\']!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 12:24:15', '2025-09-16 18:37:48', 0),
(47, 'c1b06467-31d5-4356-873a-bc4618d90a30', 'search on voted for on position [\'COO\']!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 12:24:17', '2025-09-16 18:37:48', 0),
(48, 'a5fd968c-62e2-49e5-8017-eef7d2c78b51', 'voter [\'Tijani Moro\'], loggedin, location (\'Kumasi, Ashanti, GH, 154.161.233.26\')!', 'd02df51f-85c5-4c38-a8ba-d55c1c73e4e6', 'user', 1, '2025-09-16 15:12:28', '2025-09-16 18:37:48', 0),
(49, '23ed28cb-c33e-46e5-a863-4494420ea5c6', 'voter [\'Ahmed Abass\'], loggedin, location (\'Kumasi, Ashanti, GH, 154.161.233.26\')!', 'd08df5sw-85c5-4c38-a8ba-d55c1c7708e6', 'user', 1, '2025-09-16 15:25:10', '2025-09-16 18:37:48', 0),
(50, '43228d9e-e366-4998-9ddf-4665cd397601', 'search on voted for on position [\'Organizer\']!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 15:29:25', '2025-09-16 18:37:48', 0),
(51, '350e646b-a16d-4c99-ab2d-f99e7876f9c0', 'admin [\'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d\'], profile updated!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 17:10:51', '2025-09-16 18:37:48', 0),
(52, 'dbb61e94-56ce-486f-8efe-8a6d849c43af', 'admin [\'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d\'], profile updated!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 17:10:57', '2025-09-16 18:37:48', 0),
(53, '91bc4933-d0cb-4c63-92d6-4c9aa60612c5', 'admin [\'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d\'], password changed!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 17:16:10', '2025-09-16 18:37:48', 0),
(54, 'c1575660-90ed-43dd-b80a-942d61b847c6', 'logged out from system', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 17:16:13', '2025-09-16 18:37:48', 0),
(55, '67f31e94-0e14-4ab3-bc08-646591f8b4d0', 'logged into the system', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 17:19:10', '2025-09-16 18:37:48', 0),
(56, 'ac32ca56-c4d6-4727-a417-58d1dc252d17', 'admin [\'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d\'], password changed!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 17:20:34', '2025-09-16 18:37:48', 0),
(57, '7e4dfdc2-bb77-4242-b76b-0cf69251764c', 'logged out from system', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 17:20:36', '2025-09-16 18:37:48', 0),
(58, '9708e2f4-4302-46e3-a3b5-6adf0c701c12', 'logged into the system', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 17:20:44', '2025-09-16 18:37:48', 0),
(59, 'e38c7941-c8ac-494d-8e93-78707ee4cab3', 'admin [\'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d\'], password changed!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 17:20:57', '2025-09-16 18:37:48', 0),
(60, '53732f40-2d88-4ca5-a7bc-4e9877d14b35', 'logged out from system', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 17:20:59', '2025-09-16 18:37:48', 0),
(61, 'dddffce5-3104-451a-aca0-bbb512be6cde', 'logged into the system', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 17:21:10', '2025-09-16 18:37:48', 0),
(62, '2baf34e1-c627-4174-ad64-2b5fc01d73eb', 'logged into the system', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 18:45:53', '2025-09-16 18:45:55', 0),
(63, '9295044c-cf55-49e7-921b-4c9cfb08a9eb', 'logged out from system', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 18:46:04', '2025-09-16 18:46:05', 0),
(64, 'd00ac9f4-922a-4b2c-8b7a-d3477ccb4b78', 'logged into the system', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 18:49:46', '2025-09-16 18:49:50', 0),
(65, '1c629e16-4412-47aa-b159-3acb26c98210', 'admin [\'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d\'], profile updated!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 18:50:09', '2025-09-16 18:50:10', 0),
(66, '31b4c860-6f2c-4d8a-a717-4d04f51a401b', 'admin [\'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d\'], password changed!', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 18:50:21', '2025-09-16 18:50:25', 0),
(67, '9de499b4-dac7-47f9-8be5-447823e18bab', 'logged out from system', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'admin', 1, '2025-09-16 18:50:26', '2025-09-16 18:50:30', 0);

-- --------------------------------------------------------

--
-- Table structure for table `registrars`
--

CREATE TABLE `registrars` (
  `id` int(11) NOT NULL,
  `voter_id` varchar(100) DEFAULT NULL,
  `std_id` varchar(12) NOT NULL,
  `std_password` varchar(30) NOT NULL,
  `std_fname` varchar(30) NOT NULL,
  `std_lname` varchar(30) NOT NULL,
  `std_email` varchar(200) NOT NULL,
  `vote` enum('no','yes') NOT NULL,
  `registrar_election` varchar(100) DEFAULT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrars`
--

INSERT INTO `registrars` (`id`, `voter_id`, `std_id`, `std_password`, `std_fname`, `std_lname`, `std_email`, `vote`, `registrar_election`, `status`) VALUES
(1, 'd02df51f-85c5-4c38-a8ba-d55c1c73e4e6', '202101', 'e6cnmGqX', 'tijani', 'moro', 'tijanimoro0684@outlook.com', 'no', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 1),
(2, 'd08df5sw-85c5-4c38-a8ba-d55c1c7708e6', '202102', '0SbGO5Be', 'ahmed', 'abass', 'tjhackx111@gmail.cpm', 'no', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 1),
(3, 'd02df51f-85c5-4c38-a8ba-d55c1c7708e6', '202103', '4yrFY5MD', 'aban', 'papa', 'testmetj@gmail.com', 'no', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 1),
(4, 'dcb746d4-e837-45a7-8162-2e75863e78cf', '202104', 'SlP8aurm', 'alim', 'brother', 'tijanimoro@yahoo.com', 'no', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 1),
(5, '8dfdf37a-e21b-4135-87f6-7e844d6b905e', '202106', 'p1oJ&0-D', 'madam', 'moro', 'tjhackx111@gmail.com', 'no', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 1),
(6, '3768fdc2-78a0-44b8-adaa-3efe17e25cfd', '202107', '714pvfK$', 'ummi', 'kulsum', 'tjhackx111@gmail.com', 'no', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 1),
(7, '938a5c62-39b0-4c2c-a3b8-303234218ba0', '202108', 'Nr$@2UDW', 'yahya', 'tahiru', 'tjhackx111@gmail.com', 'no', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 1),
(8, 'bc4f3278-d621-4c44-9a87-aa664a5708ec', '202109', 'KrD3@GmJ', 'akwasi', 'desmond', 'testmetj@gmail.com', 'no', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 1),
(9, 'bc4f3278-DER1-4c44-9a87-aa664a5708ec', '202111', 'ew4wfv', 'kapo', 'ali', 'tjhackx111@gmail.com', '', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 1);

-- --------------------------------------------------------

--
-- Table structure for table `voted_for`
--

CREATE TABLE `voted_for` (
  `id` bigint(20) NOT NULL,
  `for_id` varchar(100) DEFAULT NULL,
  `voter_id` varchar(100) DEFAULT NULL,
  `election_id` varchar(100) DEFAULT NULL,
  `position_id` varchar(100) DEFAULT NULL,
  `candidate_id` varchar(100) DEFAULT NULL,
  `voted_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `voted_location` varchar(255) NOT NULL,
  `voted_ip` varchar(100) NOT NULL,
  `trash` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voted_for`
--

INSERT INTO `voted_for` (`id`, `for_id`, `voter_id`, `election_id`, `position_id`, `candidate_id`, `voted_datetime`, `voted_location`, `voted_ip`, `trash`) VALUES
(1, '810edce3-52e3-45c3-a904-25d24e308e62', 'd02df51f-85c5-4c38-a8ba-d55c1c73e4e6', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '075e212d-2f94-4b3b-94ea-02e6d6e57f49', '96ffeb2e-5c09-48eb-93ab-93e554de26ce', '2025-09-16 09:19:33', 'Kumasi, GH', '154.161.245.238', 0),
(2, '636a8a0f-3ece-497a-a5cc-dcc8229471ab', 'd02df51f-85c5-4c38-a8ba-d55c1c73e4e6', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 'dcf6361c-7cbe-4775-ab35-9a3fffae310a', 'bba912ed-1f4a-4fa7-b550-5fc3bd63ba2b', '2025-09-16 09:19:33', 'Kumasi, GH', '154.161.245.238', 0),
(3, 'd820313a-eba4-4a03-85f1-fad6c5617b0f', 'd02df51f-85c5-4c38-a8ba-d55c1c73e4e6', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '7af546ef-4d89-48af-a17c-c2bc87930541', 'd47d7ac6-b696-4801-a749-721a22f225f7', '2025-09-16 09:19:33', 'Kumasi, GH', '154.161.245.238', 0),
(4, '2b0296a8-0b0f-4379-8213-aad50e40fcad', 'd02df51f-85c5-4c38-a8ba-d55c1c73e4e6', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '51ef56ad-11a9-4666-b964-2d30e9006eef', '5814420d-f91f-40dd-baf9-148ff22e9a66', '2025-09-16 09:19:33', 'Kumasi, GH', '154.161.245.238', 0),
(5, '54d36b6b-e8b6-4e25-bd0e-89b603e4a738', 'd02df51f-85c5-4c38-a8ba-d55c1c73e4e6', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '075e212d-2f94-4b3b-94ea-02e6d6e57f49', '96ffeb2e-5c09-48eb-93ab-93e554de26ce', '2025-09-16 09:28:42', 'Kumasi, GH', '154.161.245.238', 0),
(6, '78756004-92df-4300-b1fb-caa92eee7d86', 'd02df51f-85c5-4c38-a8ba-d55c1c73e4e6', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 'dcf6361c-7cbe-4775-ab35-9a3fffae310a', 'bba912ed-1f4a-4fa7-b550-5fc3bd63ba2b', '2025-09-16 09:28:42', 'Kumasi, GH', '154.161.245.238', 0),
(7, '37133f1d-9467-4c83-a23c-50a358f55fbd', 'd02df51f-85c5-4c38-a8ba-d55c1c73e4e6', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '7af546ef-4d89-48af-a17c-c2bc87930541', 'd47d7ac6-b696-4801-a749-721a22f225f7', '2025-09-16 09:28:42', 'Kumasi, GH', '154.161.245.238', 0),
(8, '63ce3541-cb3f-4694-97db-b69352de9efc', 'd02df51f-85c5-4c38-a8ba-d55c1c73e4e6', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '51ef56ad-11a9-4666-b964-2d30e9006eef', '5814420d-f91f-40dd-baf9-148ff22e9a66', '2025-09-16 09:28:42', 'Kumasi, GH', '154.161.245.238', 0),
(9, '52b9e861-c637-43b1-8fa4-5dd85e48c0b7', 'd08df5sw-85c5-4c38-a8ba-d55c1c7708e6', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '075e212d-2f94-4b3b-94ea-02e6d6e57f49', '96ffeb2e-5c09-48eb-93ab-93e554de26ce', '2025-09-16 09:33:35', 'Kumasi, GH', '154.161.245.238', 0),
(10, 'b1ce893f-3f64-4b2c-a015-ad3381c07f11', 'd08df5sw-85c5-4c38-a8ba-d55c1c7708e6', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 'dcf6361c-7cbe-4775-ab35-9a3fffae310a', '8bcb91f5-1ade-4fa5-ac44-09481ace7751', '2025-09-16 09:33:35', 'Kumasi, GH', '154.161.245.238', 0),
(11, '278c9c18-1ff6-45a0-ae9f-108c85c49499', 'd08df5sw-85c5-4c38-a8ba-d55c1c7708e6', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '7af546ef-4d89-48af-a17c-c2bc87930541', 'a09a31cc-93b5-454c-8c0e-22599e906bd6', '2025-09-16 09:33:35', 'Kumasi, GH', '154.161.245.238', 0),
(12, '2f322a80-7619-43d8-85c7-9d8ff10de4b8', 'd08df5sw-85c5-4c38-a8ba-d55c1c7708e6', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '51ef56ad-11a9-4666-b964-2d30e9006eef', '5814420d-f91f-40dd-baf9-148ff22e9a66', '2025-09-16 09:33:35', 'Kumasi, GH', '154.161.245.238', 0),
(13, 'cd996bb3-02e1-4755-b71e-fa5e3c44ae7a', '3768fdc2-78a0-44b8-adaa-3efe17e25cfd', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '075e212d-2f94-4b3b-94ea-02e6d6e57f49', '502db295-da7c-4bd3-aa13-082b1800dc5a', '2025-09-16 09:35:51', 'Kumasi, GH', '154.161.245.238', 0),
(14, '4e2ecce8-25b3-490e-99f0-7e863f9eb09f', '3768fdc2-78a0-44b8-adaa-3efe17e25cfd', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 'dcf6361c-7cbe-4775-ab35-9a3fffae310a', 'bba912ed-1f4a-4fa7-b550-5fc3bd63ba2b', '2025-09-16 09:35:51', 'Kumasi, GH', '154.161.245.238', 0),
(15, 'b4f49a26-85a4-4876-81cd-28b0dbd83b8a', '3768fdc2-78a0-44b8-adaa-3efe17e25cfd', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '7af546ef-4d89-48af-a17c-c2bc87930541', 'd47d7ac6-b696-4801-a749-721a22f225f7', '2025-09-16 09:35:51', 'Kumasi, GH', '154.161.245.238', 0),
(16, 'ff56cb0c-b582-4e95-aa59-897bd8a54f1b', '3768fdc2-78a0-44b8-adaa-3efe17e25cfd', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '51ef56ad-11a9-4666-b964-2d30e9006eef', '5814420d-f91f-40dd-baf9-148ff22e9a66', '2025-09-16 09:35:51', 'Kumasi, GH', '154.161.245.238', 0),
(17, '9238836f-cd3d-47e7-917d-c0b0cfc3ddca', 'd02df51f-85c5-4c38-a8ba-d55c1c7708e6', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '075e212d-2f94-4b3b-94ea-02e6d6e57f49', '96ffeb2e-5c09-48eb-93ab-93e554de26ce', '2025-09-16 10:04:04', 'Kumasi, GH', '154.161.245.238', 0),
(18, '678db908-e177-43ee-b5ce-9de053cb3fdc', 'd02df51f-85c5-4c38-a8ba-d55c1c7708e6', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 'dcf6361c-7cbe-4775-ab35-9a3fffae310a', 'bba912ed-1f4a-4fa7-b550-5fc3bd63ba2b', '2025-09-16 10:04:04', 'Kumasi, GH', '154.161.245.238', 0),
(19, '8ebf5dff-e5fb-4d4c-ba6c-6c6d9902f8ed', 'd02df51f-85c5-4c38-a8ba-d55c1c7708e6', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '7af546ef-4d89-48af-a17c-c2bc87930541', 'd47d7ac6-b696-4801-a749-721a22f225f7', '2025-09-16 10:04:04', 'Kumasi, GH', '154.161.245.238', 0),
(20, 'b253a341-3321-48a4-9346-e92d896c6dd0', 'd02df51f-85c5-4c38-a8ba-d55c1c7708e6', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '51ef56ad-11a9-4666-b964-2d30e9006eef', '5814420d-f91f-40dd-baf9-148ff22e9a66', '2025-09-16 10:04:04', 'Kumasi, GH', '154.161.245.238', 0),
(21, '3d140114-4cb8-4163-81de-31c4fa90ac4c', 'dcb746d4-e837-45a7-8162-2e75863e78cf', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '075e212d-2f94-4b3b-94ea-02e6d6e57f49', '502db295-da7c-4bd3-aa13-082b1800dc5a', '2025-09-16 10:24:24', 'Kumasi, GH', '154.161.245.238', 0),
(22, 'd46753b6-de47-44eb-a9ef-0d7df8ce44f9', 'dcb746d4-e837-45a7-8162-2e75863e78cf', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 'dcf6361c-7cbe-4775-ab35-9a3fffae310a', '8bcb91f5-1ade-4fa5-ac44-09481ace7751', '2025-09-16 10:24:24', 'Kumasi, GH', '154.161.245.238', 0),
(23, '3e56731b-48a7-4536-8ae1-2c6ab0207350', 'dcb746d4-e837-45a7-8162-2e75863e78cf', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '7af546ef-4d89-48af-a17c-c2bc87930541', 'a09a31cc-93b5-454c-8c0e-22599e906bd6', '2025-09-16 10:24:24', 'Kumasi, GH', '154.161.245.238', 0),
(24, 'a583e70a-1fde-45c4-a1f7-3ea5a222537e', 'dcb746d4-e837-45a7-8162-2e75863e78cf', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '51ef56ad-11a9-4666-b964-2d30e9006eef', '5814420d-f91f-40dd-baf9-148ff22e9a66', '2025-09-16 10:24:24', 'Kumasi, GH', '154.161.245.238', 0),
(25, '18a92a02-93a5-4135-a7dc-709865976f9d', 'bc4f3278-d621-4c44-9a87-aa664a5708ec', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '075e212d-2f94-4b3b-94ea-02e6d6e57f49', '96ffeb2e-5c09-48eb-93ab-93e554de26ce', '2025-09-16 10:27:34', 'Kumasi, GH', '154.161.245.238', 0),
(26, '818a9697-231c-41a7-a9e2-2d0ced8fcee2', 'bc4f3278-d621-4c44-9a87-aa664a5708ec', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '7af546ef-4d89-48af-a17c-c2bc87930541', 'a09a31cc-93b5-454c-8c0e-22599e906bd6', '2025-09-16 10:27:34', 'Kumasi, GH', '154.161.245.238', 0),
(27, '06c23e36-523a-4d24-a886-e099ec1c4119', '938a5c62-39b0-4c2c-a3b8-303234218ba0', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '075e212d-2f94-4b3b-94ea-02e6d6e57f49', '96ffeb2e-5c09-48eb-93ab-93e554de26ce', '2025-09-16 10:44:13', 'Kumasi, GH', '154.161.245.238', 0),
(28, '452249c9-24f2-4b77-bc65-07060f8377f4', '938a5c62-39b0-4c2c-a3b8-303234218ba0', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 'dcf6361c-7cbe-4775-ab35-9a3fffae310a', 'bba912ed-1f4a-4fa7-b550-5fc3bd63ba2b', '2025-09-16 10:44:13', 'Kumasi, GH', '154.161.245.238', 0),
(29, 'f004dd2b-13dc-4337-ad45-64f49e239428', '938a5c62-39b0-4c2c-a3b8-303234218ba0', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '7af546ef-4d89-48af-a17c-c2bc87930541', 'd47d7ac6-b696-4801-a749-721a22f225f7', '2025-09-16 10:44:13', 'Kumasi, GH', '154.161.245.238', 0),
(30, '10a87f8e-e743-48ca-8ea1-bbda2a228011', '8dfdf37a-e21b-4135-87f6-7e844d6b905e', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '075e212d-2f94-4b3b-94ea-02e6d6e57f49', '96ffeb2e-5c09-48eb-93ab-93e554de26ce', '2025-09-16 11:56:17', ', ', '127.0.0.1', 0),
(31, '46685204-98ba-441f-9bb6-6d39fc2169b4', '8dfdf37a-e21b-4135-87f6-7e844d6b905e', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 'dcf6361c-7cbe-4775-ab35-9a3fffae310a', 'bba912ed-1f4a-4fa7-b550-5fc3bd63ba2b', '2025-09-16 11:56:17', ', ', '127.0.0.1', 0),
(32, '822cd58e-6b37-46a6-811c-4da0815370f3', '8dfdf37a-e21b-4135-87f6-7e844d6b905e', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '7af546ef-4d89-48af-a17c-c2bc87930541', 'd47d7ac6-b696-4801-a749-721a22f225f7', '2025-09-16 11:56:17', ', ', '127.0.0.1', 0),
(33, '493cef93-cadf-4714-8eab-0aa2abb33cc0', 'bc4f3278-DER1-4c44-9a87-aa664a5708ec', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '075e212d-2f94-4b3b-94ea-02e6d6e57f49', '96ffeb2e-5c09-48eb-93ab-93e554de26ce', '2025-09-16 12:22:43', ', ', '127.0.0.1', 0),
(34, 'b06f1264-f9cd-4f75-97bc-20884ac30173', 'bc4f3278-DER1-4c44-9a87-aa664a5708ec', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', 'dcf6361c-7cbe-4775-ab35-9a3fffae310a', 'bba912ed-1f4a-4fa7-b550-5fc3bd63ba2b', '2025-09-16 12:22:43', ', ', '127.0.0.1', 0),
(35, 'd34cac71-7708-4455-a0d0-fc3b2af634e6', 'bc4f3278-DER1-4c44-9a87-aa664a5708ec', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '7af546ef-4d89-48af-a17c-c2bc87930541', 'd47d7ac6-b696-4801-a749-721a22f225f7', '2025-09-16 12:22:43', ', ', '127.0.0.1', 0),
(36, '0ee5a58f-2277-4afc-a3e4-8279689cd436', 'bc4f3278-DER1-4c44-9a87-aa664a5708ec', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '51ef56ad-11a9-4666-b964-2d30e9006eef', '5814420d-f91f-40dd-baf9-148ff22e9a66', '2025-09-16 12:22:43', ', ', '127.0.0.1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `voterhasdone`
--

CREATE TABLE `voterhasdone` (
  `id` bigint(20) NOT NULL,
  `vhd_id` varchar(100) DEFAULT NULL,
  `voter_id` varchar(100) DEFAULT NULL,
  `election_id` varchar(100) DEFAULT NULL,
  `voterhasdone_time` datetime DEFAULT current_timestamp(),
  `voterhasdone_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voterhasdone`
--

INSERT INTO `voterhasdone` (`id`, `vhd_id`, `voter_id`, `election_id`, `voterhasdone_time`, `voterhasdone_status`) VALUES
(2, 'adf76397-a330-48f1-ae63-1029c317bb2a', 'd02df51f-85c5-4c38-a8ba-d55c1c73e4e6', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '2025-09-16 09:28:42', 0),
(3, 'f834be41-4509-4563-b6bd-4f96f725890e', 'd08df5sw-85c5-4c38-a8ba-d55c1c7708e6', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '2025-09-16 09:33:35', 0),
(4, 'dbeb2b0b-0a4a-46c8-9d9d-9fe10a76e2c4', '3768fdc2-78a0-44b8-adaa-3efe17e25cfd', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '2025-09-16 09:35:51', 0),
(5, '261727d6-8c4f-4770-81dc-7e0f7859b972', 'd02df51f-85c5-4c38-a8ba-d55c1c7708e6', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '2025-09-16 10:04:04', 0),
(6, '362c8dfd-d5e7-4515-9817-c88e3a80bce6', 'dcb746d4-e837-45a7-8162-2e75863e78cf', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '2025-09-16 10:24:24', 0),
(7, '1c2e7a70-e760-41d3-a679-175b9fc82a9e', 'bc4f3278-d621-4c44-9a87-aa664a5708ec', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '2025-09-16 10:27:34', 0),
(8, '2288e263-50b1-41e4-8868-a9497183aed7', '938a5c62-39b0-4c2c-a3b8-303234218ba0', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '2025-09-16 10:44:13', 0),
(9, '0c5d3d72-10b9-4885-80e6-df59e45838bf', '8dfdf37a-e21b-4135-87f6-7e844d6b905e', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '2025-09-16 11:56:17', 0),
(10, '95c3bcff-9832-4e88-b4d8-8d9824d47720', 'bc4f3278-DER1-4c44-9a87-aa664a5708ec', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5', '2025-09-16 12:22:43', 0);

-- --------------------------------------------------------

--
-- Table structure for table `voter_login_details`
--

CREATE TABLE `voter_login_details` (
  `id` bigint(20) NOT NULL,
  `voter_login_details_id` varchar(100) DEFAULT NULL,
  `voter_id` varchar(100) DEFAULT NULL,
  `details_location` varchar(500) DEFAULT NULL,
  `voter_login_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `voter_logout_datetime` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `voter_login_details_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voter_login_details`
--

INSERT INTO `voter_login_details` (`id`, `voter_login_details_id`, `voter_id`, `details_location`, `voter_login_datetime`, `voter_logout_datetime`, `voter_login_details_status`) VALUES
(1, 'a1fbb25e-114c-4c8b-b37e-5eafa4e7a66b', 'd02df51f-85c5-4c38-a8ba-d55c1c73e4e6', NULL, '2025-09-16 08:10:42', NULL, 0),
(2, 'f87691b7-30f6-4792-a479-4ea661888b52', 'd02df51f-85c5-4c38-a8ba-d55c1c73e4e6', NULL, '2025-09-16 08:13:04', NULL, 0),
(3, '1a7085a9-1c2e-4cfb-8c41-000406d4401b', 'd02df51f-85c5-4c38-a8ba-d55c1c73e4e6', NULL, '2025-09-16 09:24:07', NULL, 0),
(4, '7f858ac1-feeb-4c37-b9ab-6e575be925ea', 'd02df51f-85c5-4c38-a8ba-d55c1c73e4e6', NULL, '2025-09-16 09:30:32', NULL, 0),
(5, 'd702af50-f9b6-4adf-a0c3-8588e88b4898', 'd08df5sw-85c5-4c38-a8ba-d55c1c7708e6', NULL, '2025-09-16 09:32:52', NULL, 0),
(6, '4ef42b9f-ef75-4a18-ae0d-db7869426470', '3768fdc2-78a0-44b8-adaa-3efe17e25cfd', NULL, '2025-09-16 09:35:24', NULL, 0),
(7, '6cc8a423-ae4e-4202-9151-3307b1af1be1', 'd02df51f-85c5-4c38-a8ba-d55c1c7708e6', NULL, '2025-09-16 09:57:12', NULL, 0),
(8, '1918e5e8-c472-4f46-b386-a1a062dc32ef', 'dcb746d4-e837-45a7-8162-2e75863e78cf', NULL, '2025-09-16 10:23:34', NULL, 0),
(9, '9ebbd70a-9051-4a25-b53a-269ee305bfff', 'bc4f3278-d621-4c44-9a87-aa664a5708ec', NULL, '2025-09-16 10:26:41', NULL, 0),
(10, '078335ee-31b7-4e07-aaa7-c083ee321fa6', '938a5c62-39b0-4c2c-a3b8-303234218ba0', NULL, '2025-09-16 10:43:37', NULL, 0),
(11, 'faf1687d-28a8-4900-9d61-9d82ea103d5a', '8dfdf37a-e21b-4135-87f6-7e844d6b905e', NULL, '2025-09-16 11:12:06', NULL, 0),
(12, '954a0cb5-916d-4f3e-9884-e51e18a4cf45', 'bc4f3278-DER1-4c44-9a87-aa664a5708ec', NULL, '2025-09-16 12:02:41', NULL, 0),
(13, '4c63d6a5-2ac2-4761-bf91-2a5f17f8d515', 'bc4f3278-DER1-4c44-9a87-aa664a5708ec', NULL, '2025-09-16 12:05:52', NULL, 0),
(14, '77dc036b-b432-490c-9a60-1f3ee69a20b5', 'd02df51f-85c5-4c38-a8ba-d55c1c73e4e6', NULL, '2025-09-16 15:12:28', '2025-09-16 15:12:31', 1),
(15, '353fbe4a-4285-4667-82d7-c16c4af122dd', 'd08df5sw-85c5-4c38-a8ba-d55c1c7708e6', 'Kumasi, Ashanti, GH, 154.161.233.26', '2025-09-16 15:25:10', '2025-09-16 15:27:37', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vote_counts`
--

CREATE TABLE `vote_counts` (
  `id` bigint(20) NOT NULL,
  `vote_count_id` varchar(100) DEFAULT NULL,
  `results` int(11) NOT NULL,
  `results_no` int(11) NOT NULL,
  `contestant_id` varchar(100) DEFAULT NULL,
  `position_id` varchar(100) DEFAULT NULL,
  `election_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vote_counts`
--

INSERT INTO `vote_counts` (`id`, `vote_count_id`, `results`, `results_no`, `contestant_id`, `position_id`, `election_id`) VALUES
(1, NULL, 7, 0, '96ffeb2e-5c09-48eb-93ab-93e554de26ce', '075e212d-2f94-4b3b-94ea-02e6d6e57f49', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5'),
(2, NULL, 2, 0, '502db295-da7c-4bd3-aa13-082b1800dc5a', '075e212d-2f94-4b3b-94ea-02e6d6e57f49', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5'),
(3, NULL, 3, 3, '5814420d-f91f-40dd-baf9-148ff22e9a66', '51ef56ad-11a9-4666-b964-2d30e9006eef', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5'),
(4, NULL, 6, 0, 'd47d7ac6-b696-4801-a749-721a22f225f7', '7af546ef-4d89-48af-a17c-c2bc87930541', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5'),
(5, NULL, 3, 0, 'a09a31cc-93b5-454c-8c0e-22599e906bd6', '7af546ef-4d89-48af-a17c-c2bc87930541', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5'),
(6, NULL, 2, 0, '8bcb91f5-1ade-4fa5-ac44-09481ace7751', 'dcf6361c-7cbe-4775-ab35-9a3fffae310a', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5'),
(7, NULL, 6, 0, 'bba912ed-1f4a-4fa7-b550-5fc3bd63ba2b', 'dcf6361c-7cbe-4775-ab35-9a3fffae310a', '9fc34fa8-ce76-4fc8-9ee4-e79caba5bee5');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cont_details`
--
ALTER TABLE `cont_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `election`
--
ALTER TABLE `election`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `puubu_admin`
--
ALTER TABLE `puubu_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `puubu_election_logs`
--
ALTER TABLE `puubu_election_logs`
  ADD PRIMARY KEY (`puubu_election_logs_id`);

--
-- Indexes for table `puubu_logs`
--
ALTER TABLE `puubu_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `createdAt` (`createdAt`),
  ADD KEY `log_status` (`log_status`),
  ADD KEY `log_admin` (`log_person`),
  ADD KEY `log_id` (`log_id`);

--
-- Indexes for table `registrars`
--
ALTER TABLE `registrars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `election_type` (`registrar_election`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voter_login_details`
--
ALTER TABLE `voter_login_details`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `election`
--
ALTER TABLE `election`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `puubu_admin`
--
ALTER TABLE `puubu_admin`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `puubu_election_logs`
--
ALTER TABLE `puubu_election_logs`
  MODIFY `puubu_election_logs_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `puubu_logs`
--
ALTER TABLE `puubu_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `registrars`
--
ALTER TABLE `registrars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `voted_for`
--
ALTER TABLE `voted_for`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `voterhasdone`
--
ALTER TABLE `voterhasdone`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `voter_login_details`
--
ALTER TABLE `voter_login_details`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `vote_counts`
--
ALTER TABLE `vote_counts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
