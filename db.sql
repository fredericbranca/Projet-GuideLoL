-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour guidelol
CREATE DATABASE IF NOT EXISTS `guidelol` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `guidelol`;

-- Listage de la structure de table guidelol. data_champion
CREATE TABLE IF NOT EXISTS `data_champion` (
  `id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table guidelol.data_champion : ~4 rows (environ)
INSERT INTO `data_champion` (`id`) VALUES
	(12),
	(16),
	(25),
	(50);

-- Listage de la structure de table guidelol. data_sort_invocateur
CREATE TABLE IF NOT EXISTS `data_sort_invocateur` (
  `id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table guidelol.data_sort_invocateur : ~4 rows (environ)
INSERT INTO `data_sort_invocateur` (`id`) VALUES
	(1),
	(2),
	(3),
	(4);

-- Listage de la structure de table guidelol. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table guidelol.doctrine_migration_versions : ~1 rows (environ)
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20230912080757', '2023-09-12 08:08:04', 37);

-- Listage de la structure de table guidelol. guide
CREATE TABLE IF NOT EXISTS `guide` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `voie` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `modified_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `champion_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CA9EC735FA7FD7EB` (`champion_id`),
  CONSTRAINT `FK_CA9EC735FA7FD7EB` FOREIGN KEY (`champion_id`) REFERENCES `data_champion` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table guidelol.guide : ~0 rows (environ)

-- Listage de la structure de table guidelol. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table guidelol.messenger_messages : ~0 rows (environ)

-- Listage de la structure de table guidelol. sort_invocateur
CREATE TABLE IF NOT EXISTS `sort_invocateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `guide_id` int NOT NULL,
  `titre` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commentaire` longtext COLLATE utf8mb4_unicode_ci,
  `ordre` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CEBB8376D7ED1D4B` (`guide_id`),
  CONSTRAINT `FK_CEBB8376D7ED1D4B` FOREIGN KEY (`guide_id`) REFERENCES `guide` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table guidelol.sort_invocateur : ~0 rows (environ)

-- Listage de la structure de table guidelol. sort_invocateur_data_sort_invocateur
CREATE TABLE IF NOT EXISTS `sort_invocateur_data_sort_invocateur` (
  `sort_invocateur_id` int NOT NULL,
  `data_sort_invocateur_id` int NOT NULL,
  PRIMARY KEY (`sort_invocateur_id`,`data_sort_invocateur_id`),
  KEY `IDX_14881CBB7A207090` (`sort_invocateur_id`),
  KEY `IDX_14881CBB50930EAF` (`data_sort_invocateur_id`),
  CONSTRAINT `FK_14881CBB50930EAF` FOREIGN KEY (`data_sort_invocateur_id`) REFERENCES `data_sort_invocateur` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_14881CBB7A207090` FOREIGN KEY (`sort_invocateur_id`) REFERENCES `sort_invocateur` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table guidelol.sort_invocateur_data_sort_invocateur : ~0 rows (environ)

-- Listage de la structure de table guidelol. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table guidelol.user : ~0 rows (environ)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;