-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 25-08-25 11:31
-- 서버 버전: 10.4.32-MariaDB
-- PHP 버전: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 데이터베이스: `confrim`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `last_login`, `created_at`) VALUES
(1, 'admin', '$2y$10$vI8fEgQIdRwgOR7HXr6g3.hP.EHmDsdS8ugP9tz8AYy8y3K2ZxU0.', '2025-08-25 07:13:15', '2025-08-25 06:15:47');

-- --------------------------------------------------------

--
-- 테이블 구조 `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `clients`
--

INSERT INTO `clients` (`id`, `name`, `contact`, `email`, `phone`, `created_at`) VALUES
(1, '코인트', '이승진', 'coint@naver.com', '010-1111-1111', '2025-08-25 06:16:24');

-- --------------------------------------------------------

--
-- 테이블 구조 `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `deposit` decimal(15,2) DEFAULT 0.00,
  `middle_payment` decimal(15,2) DEFAULT 0.00,
  `balance` decimal(15,2) DEFAULT 0.00,
  `outsourcing_cost` decimal(15,2) DEFAULT 0.00,
  `misc_cost` decimal(15,2) DEFAULT 0.00,
  `profit` decimal(15,2) DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('진행중','완료','보류','취소') DEFAULT '진행중',
  `memo` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `projects`
--

INSERT INTO `projects` (`id`, `client_id`, `title`, `amount`, `deposit`, `middle_payment`, `balance`, `outsourcing_cost`, `misc_cost`, `profit`, `description`, `start_date`, `end_date`, `status`, `memo`, `created_at`, `updated_at`) VALUES
(1, 1, '스푼TV 대시보드 개발', 1500000.00, 0.00, 0.00, 1500000.00, 0.00, 0.00, 1500000.00, 'PHP + MYSQL로 개발', '2025-08-25', '0000-00-00', '진행중', '화이팅', '2025-08-25 07:59:03', '2025-08-25 07:59:03');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- 테이블의 인덱스 `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- 테이블의 인덱스 `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 테이블의 AUTO_INCREMENT `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 테이블의 AUTO_INCREMENT `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 덤프된 테이블의 제약사항
--

--
-- 테이블의 제약사항 `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
