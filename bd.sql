-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.0.28 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para rockr
DROP DATABASE IF EXISTS `rockr`;
CREATE DATABASE IF NOT EXISTS `rockr` /*!40100 DEFAULT CHARACTER SET latin1 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `rockr`;

-- Copiando estrutura para tabela rockr.investments
DROP TABLE IF EXISTS `investments`;
CREATE TABLE IF NOT EXISTS `investments` (
  `id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `transaction_user_id` varchar(50) NOT NULL,
  `transaction_investiment_id` int NOT NULL DEFAULT '0',
  `transaction_type` varchar(50) NOT NULL,
  `transaction_ammount` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `transaction_date` date NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `transaction_investiment_id` (`transaction_investiment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela rockr.investments: ~0 rows (aproximadamente)
DELETE FROM `investments`;
/*!40000 ALTER TABLE `investments` DISABLE KEYS */;
/*!40000 ALTER TABLE `investments` ENABLE KEYS */;

-- Copiando estrutura para tabela rockr.products
DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `investment_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `investment_min_value` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela rockr.products: ~3 rows (aproximadamente)
DELETE FROM `products`;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` (`id`, `investment_name`, `investment_min_value`) VALUES
	(1, 'Título Público', 200.00),
	(2, 'CDB', 300.00),
	(3, 'Tesouro Direto', 100.00);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

-- Copiando estrutura para tabela rockr.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_email` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `user_pass` varchar(256) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `user_balance` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela rockr.users: ~0 rows (aproximadamente)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `user_name`, `user_email`, `user_pass`, `user_balance`, `created_at`, `updated_at`, `deleted_at`) VALUES
	('BWDT', 'rockr', 'rockr@example.com', 'd033e22ae348aeb5660fc2140aec35850c4da997', 10000.00, '2022-03-11 20:30:31', '2022-03-13 20:18:36', NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Copiando estrutura para tabela rockr.withdrawal
DROP TABLE IF EXISTS `withdrawal`;
CREATE TABLE IF NOT EXISTS `withdrawal` (
  `id` varchar(50) NOT NULL DEFAULT '',
  `investment_id` varchar(50) NOT NULL DEFAULT '',
  `valor_investido` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_meses` int NOT NULL DEFAULT '0',
  `taxa_sobre_lucro` decimal(15,2) NOT NULL,
  `lucro` decimal(15,2) NOT NULL,
  `valor_descontado_do_lucro` decimal(15,2) NOT NULL,
  `lucro_ja_taxado` decimal(15,2) NOT NULL,
  `saldo_final` decimal(15,2) NOT NULL,
  `transaction_date` date NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `investment_id` (`investment_id`),
  CONSTRAINT `FK_withdrawal_investments` FOREIGN KEY (`investment_id`) REFERENCES `investments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela rockr.withdrawal: ~0 rows (aproximadamente)
DELETE FROM `withdrawal`;
/*!40000 ALTER TABLE `withdrawal` DISABLE KEYS */;
/*!40000 ALTER TABLE `withdrawal` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
