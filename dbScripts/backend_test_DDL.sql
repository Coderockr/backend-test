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

-- --------------------------------------------------------

--
-- Table structure for table `balance`
--

CREATE TABLE `balance` (
  `id` int UNSIGNED NOT NULL,
  `idInvestor` mediumint UNSIGNED NOT NULL,
  `idInvestment` int UNSIGNED NOT NULL,
  `currentBalance` decimal(10,2) NOT NULL,
  `gain` decimal(10,2) NOT NULL,
  `balanceDate` date NOT NULL,
  `createdAt` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `investment`
--

CREATE TABLE `investment` (
  `id` int UNSIGNED NOT NULL,
  `idInvestor` mediumint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `withdrew` tinyint(1) NOT NULL,
  `investmentDate` date NOT NULL,
  `createdAt` timestamp NOT NULL,
  `updatedAt` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `investor`
--

CREATE TABLE `investor` (
  `id` mediumint UNSIGNED NOT NULL,
  `login` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstName` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastName` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `genre` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthDate` date NOT NULL,
  `createdAt` timestamp NOT NULL,
  `updatedAt` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_investment_overview`
-- (See below for the actual view)
--
CREATE TABLE `vw_investment_overview` (
`currentAmount` decimal(10,2)
,`idInvestment` int unsigned
,`idInvestor` mediumint unsigned
,`initialAmount` decimal(10,2)
,`investmentDate` date
,`lastInvestmentUpdate` date
,`period` decimal(42,0)
,`totalGain` decimal(32,2)
,`withdrew` tinyint(1)
);

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal`
--

CREATE TABLE `withdrawal` (
  `id` int UNSIGNED NOT NULL,
  `idInvestment` int UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `createdAt` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure for view `vw_investment_overview`
--
DROP TABLE IF EXISTS `vw_investment_overview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_investment_overview`  AS  select `inv`.`id` AS `idInvestment`,`inv`.`idInvestor` AS `idInvestor`,`inv`.`investmentDate` AS `investmentDate`,min(`bal`.`currentBalance`) AS `initialAmount`,max(`bal`.`currentBalance`) AS `currentAmount`,sum(`bal`.`gain`) AS `totalGain`,`inv`.`withdrew` AS `withdrew`,max(`bal`.`balanceDate`) AS `lastInvestmentUpdate`,sum(timestampdiff(MONTH,`inv`.`investmentDate`,`bal`.`balanceDate`)) AS `period` from (`investment` `inv` join `balance` `bal` on((`inv`.`id` = `bal`.`idInvestment`))) group by `inv`.`id`,`inv`.`investmentDate`,`inv`.`idInvestor`,`inv`.`withdrew` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `balance`
--
ALTER TABLE `balance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_balance_id_investor` (`idInvestor`),
  ADD KEY `fk_balance_id_investment` (`idInvestment`);

--
-- Indexes for table `investment`
--
ALTER TABLE `investment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_investment_id_investor` (`idInvestor`);

--
-- Indexes for table `investor`
--
ALTER TABLE `investor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_investor_login` (`login`);

--
-- Indexes for table `withdrawal`
--
ALTER TABLE `withdrawal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_withdrawal_id_investment` (`idInvestment`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `balance`
--
ALTER TABLE `balance`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investment`
--
ALTER TABLE `investment`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investor`
--
ALTER TABLE `investor`
  MODIFY `id` mediumint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdrawal`
--
ALTER TABLE `withdrawal`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `balance`
--
ALTER TABLE `balance`
  ADD CONSTRAINT `fk_balance_id_investment` FOREIGN KEY (`idInvestment`) REFERENCES `investment` (`id`),
  ADD CONSTRAINT `fk_balance_id_investor` FOREIGN KEY (`idInvestor`) REFERENCES `investor` (`id`);

--
-- Constraints for table `investment`
--
ALTER TABLE `investment`
  ADD CONSTRAINT `fk_investment_id_investor` FOREIGN KEY (`idInvestor`) REFERENCES `investor` (`id`);

--
-- Constraints for table `withdrawal`
--
ALTER TABLE `withdrawal`
  ADD CONSTRAINT `fk_withdrawal_id_investment` FOREIGN KEY (`idInvestment`) REFERENCES `investment` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
