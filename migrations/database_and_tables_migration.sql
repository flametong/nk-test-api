-- Дамп структуры базы данных nk
DROP DATABASE IF EXISTS `nk`;
CREATE DATABASE IF NOT EXISTS `nk` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `nk`;

-- Дамп структуры для таблица nk.categories
DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `uri` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uri_unique` (`uri`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Дамп данных таблицы nk.categories: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `title`, `uri`) VALUES
	(1, 'dairy', 'dairy'),
	(2, 'bakery', 'bakery'),
	(3, 'food', 'food'),
	(4, 'furniture', 'furniture');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Дамп структуры для таблица nk.goods
DROP TABLE IF EXISTS `goods`;
CREATE TABLE IF NOT EXISTS `goods` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `inn` varchar(12) NOT NULL,
  `barcode` varchar(13) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `barcode_unique` (`barcode`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Дамп данных таблицы nk.goods: ~6 rows (приблизительно)
/*!40000 ALTER TABLE `goods` DISABLE KEYS */;
INSERT INTO `goods` (`id`, `title`, `inn`, `barcode`, `description`, `price`) VALUES
	(1, 'Bread', '7743013902', '4820024700016', 'Bread description', 79.90),
	(4, 'Yogurt', '7743013911', '2280024700016', 'Yogurt description', 39.90),
	(13, 'Sofa', '8743013911', '2280024701000', 'Sofa description', 19999.99),
	(14, 'Bed', '8743013912', '2280024701001', 'Bed description', 9999.99),
	(15, 'Closet', '8743013913', '2280024701002', 'Closet description', 29999.99),
	(16, 'Bun', '8743013914', '2280024701003', 'Bun description', 39.99);
/*!40000 ALTER TABLE `goods` ENABLE KEYS */;

-- Дамп структуры для таблица nk.goods_categories
DROP TABLE IF EXISTS `goods_categories`;
CREATE TABLE IF NOT EXISTS `goods_categories` (
  `goods_id` int unsigned NOT NULL,
  `category_id` int unsigned NOT NULL,
  PRIMARY KEY (`goods_id`,`category_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `goods_categories_ibfk_1` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`),
  CONSTRAINT `goods_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Дамп данных таблицы nk.goods_categories: ~9 rows (приблизительно)
/*!40000 ALTER TABLE `goods_categories` DISABLE KEYS */;
INSERT INTO `goods_categories` (`goods_id`, `category_id`) VALUES
	(4, 1),
	(1, 2),
	(16, 2),
	(1, 3),
	(4, 3),
	(16, 3),
	(13, 4),
	(14, 4),
	(15, 4);
/*!40000 ALTER TABLE `goods_categories` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
