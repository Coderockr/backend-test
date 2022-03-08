-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `backend_test`
--

--
-- Dumping data for table `investor`
--

INSERT INTO `investor` (`id`, `login`, `password`, `firstName`, `lastName`, `genre`, `birthDate`, `createdAt`, `updatedAt`) VALUES
(1, 'teste@teste.com', '$2y$10$Gn7oXh.eQKoQ4rp3VJ1p0uzfqW0ALMIhJunH5.nR6vAWOdaFfS19K', 'Teste', 'Silva', 'M', '1990-05-01', '2022-03-07 01:37:01', '2022-03-07 01:37:01');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
