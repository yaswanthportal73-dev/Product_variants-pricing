-- MySQL dump 10.13  Distrib 8.0.44, for Win64 (x86_64)
--
-- Host: localhost    Database: grocery_db
-- ------------------------------------------------------
-- Server version	8.0.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `brands` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (18,'vegetables',1,'2025-12-31 07:06:18','2025-12-31 07:06:18'),(19,'juicess',1,'2026-01-02 06:51:36','2026-01-02 06:51:36');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (15,'Vegetables','vegetables','c470cf0fc646e67bfa56d36359a6abd5.jpg',1,'2025-12-31 02:31:30','2025-12-31 02:31:30'),(16,'Juices','juices','49f5c8160e1aa4c93e5a14635b09872f.jpg',1,'2025-12-31 02:33:59','2025-12-31 02:33:59'),(17,'vegetables','vegetables-1','754146c2220bf0abe58f52c48e47db98.jpg',1,'2025-12-31 02:39:43','2025-12-31 02:39:43');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `countries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `country_name` varchar(150) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` varchar(100) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `display_order` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `districts`
--

DROP TABLE IF EXISTS `districts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `districts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `district_name` varchar(255) NOT NULL,
  `state_id` int NOT NULL,
  `country_id` int NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_by` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `deleted_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `state_id` (`state_id`),
  KEY `country_id` (`country_id`),
  CONSTRAINT `districts_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE,
  CONSTRAINT `districts_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `districts`
--

LOCK TABLES `districts` WRITE;
/*!40000 ALTER TABLE `districts` DISABLE KEYS */;
/*!40000 ALTER TABLE `districts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `plain_password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location1`
--

DROP TABLE IF EXISTS `location1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `location1` (
  `id` int NOT NULL AUTO_INCREMENT,
  `country_name` varchar(100) DEFAULT NULL,
  `state_name` varchar(100) DEFAULT NULL,
  `district_name` varchar(100) DEFAULT NULL,
  `mandal_name` varchar(100) DEFAULT NULL,
  `village_name` varchar(100) DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `deleted_by` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location1`
--

LOCK TABLES `location1` WRITE;
/*!40000 ALTER TABLE `location1` DISABLE KEYS */;
/*!40000 ALTER TABLE `location1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mandals`
--

DROP TABLE IF EXISTS `mandals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mandals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mandal_name` varchar(150) NOT NULL,
  `country_id` int NOT NULL,
  `state_id` int NOT NULL,
  `district_id` int NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_by` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `deleted_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`),
  KEY `state_id` (`state_id`),
  KEY `district_id` (`district_id`),
  CONSTRAINT `mandals_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mandals_ibfk_2` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mandals_ibfk_3` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mandals`
--

LOCK TABLES `mandals` WRITE;
/*!40000 ALTER TABLE `mandals` DISABLE KEYS */;
/*!40000 ALTER TABLE `mandals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_methods`
--

DROP TABLE IF EXISTS `payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_methods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) NOT NULL,
  `extra_charge` decimal(10,2) DEFAULT '0.00',
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_methods`
--

LOCK TABLES `payment_methods` WRITE;
/*!40000 ALTER TABLE `payment_methods` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (16,10,'bcd801e28563fed215607cdf18a4076c.jpg',1,'2025-12-31 06:01:50'),(17,12,'6e308b8a70ff98a31c6f86effeb8acab.jpg',1,'2025-12-31 12:00:19'),(18,13,'79d4cfc5f57e32f0037daca897c582fe.jpg',1,'2025-12-31 12:02:13'),(19,14,'df4536aa52082fd9cf24f77f5b56349d.jpg',1,'2025-12-31 23:38:42'),(20,15,'f6c59d4dfdc65fa8dc358135b9db16bf.jpg',1,'2026-01-02 02:06:46'),(21,16,'da3a1f2ff9f99c9bd01d8c6d852712e6.jpg',1,'2026-01-02 02:08:02'),(22,18,'8a9d8c28abfafcdad02a8ef5aeaa4eda.jpg',1,'2026-01-02 02:16:51'),(23,23,'54f6a06b9ace31da36d6de27843a8828.jpg',1,'2026-01-02 02:42:13'),(24,32,'d1ba7f460914e58453077ca01e7994ff.jpg',1,'2026-01-02 13:41:49'),(25,32,'9c8e76f52b7ebdc131f83d2dc9204c35.jpg',0,'2026-01-02 13:41:49'),(26,33,'3dbd33b29b105123ad3e8bd10fd42858.jpg',1,'2026-01-02 14:33:23'),(27,34,'2964c47f93a37691cd7f628030fcfcec.jpg',1,'2026-01-02 14:34:52');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_variants` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `unit_id` int NOT NULL,
  `volume` decimal(10,2) NOT NULL DEFAULT '0.00',
  `purchase_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `stock` int NOT NULL DEFAULT '0',
  `alert_stock` int DEFAULT '5',
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `unit_id` (`unit_id`),
  CONSTRAINT `fk_variant_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_variant_unit` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
INSERT INTO `product_variants` VALUES (1,31,20,1.00,50.00,60.00,0,5,1,'2026-01-02 13:31:01','2026-01-02 18:01:01'),(2,31,20,0.50,25.00,30.00,0,5,1,'2026-01-02 13:31:01','2026-01-02 18:01:01'),(6,32,20,1.00,20.00,30.00,0,5,1,'2026-01-02 14:04:48','2026-01-02 18:34:48'),(7,32,20,2.00,40.00,60.00,0,5,1,'2026-01-02 14:04:48','2026-01-02 18:34:48'),(8,33,20,1.00,40.00,50.00,0,5,1,'2026-01-02 14:33:23','2026-01-02 19:03:23'),(9,34,20,1.00,20.00,40.00,0,5,1,'2026-01-02 14:34:52','2026-01-02 19:04:52'),(10,34,20,2.00,40.00,80.00,0,5,1,'2026-01-02 14:34:52','2026-01-02 19:04:52'),(11,35,20,1.00,0.00,0.00,0,5,1,'2026-01-02 14:36:09','2026-01-02 19:06:09'),(12,35,20,13.00,0.00,0.00,0,5,1,'2026-01-02 14:36:09','2026-01-02 19:06:09'),(13,36,20,1.00,20.00,30.00,0,5,1,'2026-01-04 23:58:13','2026-01-05 04:28:13'),(14,36,20,2.00,40.00,60.00,0,5,1,'2026-01-04 23:58:13','2026-01-05 04:28:13');
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `barcode` varchar(50) DEFAULT NULL,
  `barcode_type` enum('EAN13','UPC','CODE128','CODE39') DEFAULT 'EAN13',
  `item_code` varchar(100) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `sub_category_id` int DEFAULT NULL,
  `brand_id` int DEFAULT NULL,
  `unit_id` int DEFAULT NULL,
  `supplier_id` int DEFAULT NULL,
  `size` varchar(100) DEFAULT NULL,
  `short_description` text,
  `description` text,
  `cost_price` decimal(15,2) DEFAULT '0.00',
  `original_price` decimal(10,2) DEFAULT NULL,
  `selling_price` decimal(15,2) DEFAULT '0.00',
  `discount_type` enum('percentage','flat') DEFAULT NULL,
  `discount_value` decimal(15,2) DEFAULT '0.00',
  `discount_amount` decimal(15,2) DEFAULT '0.00',
  `final_price` decimal(10,2) NOT NULL,
  `quantity` int DEFAULT '0',
  `alert_quantity` int DEFAULT '5',
  `min_order_qty` int DEFAULT '1',
  `max_order_qty` int DEFAULT '10',
  `expiry_date` date DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `barcode` (`barcode`),
  UNIQUE KEY `item_code` (`item_code`),
  KEY `category_id` (`category_id`),
  KEY `brand_id` (`brand_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `unit_id` (`unit_id`),
  KEY `fk_products_subcategory` (`sub_category_id`),
  CONSTRAINT `fk_products_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_products_subcategory` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_products_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_products_unit` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (8,'ladies fingure','4281037047725','EAN13','PROD-666989910876',15,NULL,18,NULL,14,NULL,'fssdfgf','<p>sdfgggf</p>\r\n',NULL,0.00,0.00,NULL,0.00,0.00,0.00,NULL,5,1,10,NULL,1,'2025-12-31 04:23:39','2026-01-05 05:07:09',0,'2025-12-31 17:31:04'),(9,'ladies fingure','4607706329839','EAN13','PROD-768708400947',15,16,18,NULL,14,NULL,'gfhgf','<p>jhghjfhj</p>\r\n',NULL,0.00,0.00,NULL,0.00,0.00,0.00,NULL,5,1,10,NULL,1,'2025-12-31 06:00:23','2026-01-05 05:08:44',1,'2026-01-05 06:08:44'),(10,'ladies fingure','8249245642398','EAN13','PROD-770418980873',15,16,18,NULL,14,NULL,'hgfffd','<p>jhhfxfd</p>\r\n',NULL,0.00,0.00,NULL,0.00,0.00,0.00,NULL,5,1,10,NULL,1,'2025-12-31 06:01:50','2026-01-05 05:08:49',1,'2026-01-05 06:08:49'),(12,'ladies fingure','8958538815912','EAN13','PROD-985709530785',15,16,18,NULL,14,NULL,'sdfee','<p>erweqee</p>\r\n',NULL,0.00,0.00,NULL,0.00,0.00,0.00,NULL,5,1,10,NULL,1,'2025-12-31 12:00:19','2026-01-05 05:07:09',0,'2025-12-31 17:31:01'),(13,'ladies fingure','6413071357029','EAN13','PROD-986842770771',15,16,18,NULL,14,NULL,'sffs','<p>sffdfd</p>\r\n',NULL,0.00,0.00,NULL,0.00,0.00,0.00,NULL,5,1,10,NULL,1,'2025-12-31 12:02:13','2026-01-05 05:08:37',1,'2026-01-05 06:08:37'),(14,'ladies fingure','4090518098732','EAN13','PROD-20260101-4087',15,16,18,NULL,14,NULL,'jhfhjf','<p>jghjfh</p>\r\n',NULL,0.00,0.00,NULL,0.00,0.00,0.00,NULL,5,1,10,NULL,0,'2025-12-31 23:38:41','2026-01-01 04:08:41',0,NULL),(15,'ladies fingure','5507607913306','EAN13','PROD-20260102-3552',15,16,18,NULL,14,NULL,'fdbf','<p>dfgfgf</p>\r\n',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,0,'2026-01-02 02:06:46','2026-01-02 06:36:46',0,NULL),(16,'ladies fingure','7596633641091','EAN13','PROD-20260102-5951',15,16,18,NULL,14,NULL,'gfsddgf','<p>dfdgg</p>\r\n',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,0,'2026-01-02 02:08:02','2026-01-02 06:38:02',0,NULL),(17,'ladies fingure','4817188183083','EAN13','PROD-20260102-2174',15,16,NULL,NULL,NULL,NULL,'','',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,0,'2026-01-02 02:10:05','2026-01-02 06:40:05',0,NULL),(18,'ladies fingure','9822922623737','EAN13','PROD-20260102-7825',15,16,18,NULL,14,NULL,'gfdd','<p>fdfgg</p>\r\n',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,1,'2026-01-02 02:16:51','2026-01-05 05:07:09',0,'2026-01-02 12:12:55'),(19,'ladies fingure','8974896690213','EAN13','PROD-20260102-2986',15,16,18,NULL,14,NULL,'dfsdcds','<p>sdf</p>\r\n',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,0,'2026-01-02 02:19:07','2026-01-02 06:49:07',0,NULL),(20,'Juices','2910201292921','EAN13','PROD-20260102-2878',16,17,19,NULL,14,NULL,'kh','<p>kj</p>\r\n',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,0,'2026-01-02 02:23:17','2026-01-02 06:53:17',0,NULL),(21,'ladies fingure','9572692389004','EAN13','PROD-20260102-5671',16,17,NULL,NULL,NULL,NULL,'','',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,0,'2026-01-02 02:29:34','2026-01-02 06:59:34',0,NULL),(22,'ladies fingure','6870903455689','EAN13','PROD-20260102-4123',16,17,18,NULL,NULL,NULL,'','',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,0,'2026-01-02 02:40:59','2026-01-02 07:10:59',0,NULL),(23,'ladies fingure','5608152731770','EAN13','PROD-20260102-4067',16,17,19,NULL,14,NULL,'hgfdd','<p>fggsss</p>\r\n',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,0,'2026-01-02 02:42:13','2026-01-02 07:12:13',0,NULL),(24,'ladies fingure','8411680812258','EAN13','PROD-20260102-1036',16,17,19,NULL,14,NULL,'','',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,0,'2026-01-02 02:53:54','2026-01-02 07:23:54',0,NULL),(25,'jui','6284925498515','EAN13','PROD-20260102-3422',16,17,19,NULL,14,NULL,'ww','',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,0,'2026-01-02 02:54:41','2026-01-02 07:24:41',0,NULL),(26,'ladies fingure','8876536059261','EAN13','PROD-20260102-6085',16,NULL,NULL,NULL,NULL,NULL,'','',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,0,'2026-01-02 05:07:15','2026-01-02 09:37:15',0,NULL),(27,'ladies fingure','9954116995037','EAN13','PROD-20260102-5716',16,17,19,NULL,14,NULL,'hggfs','<p>gfddd</p>\r\n',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,1,'2026-01-02 06:44:16','2026-01-05 05:08:58',1,'2026-01-05 06:08:58'),(29,'ladies fingure','5024156995534','EAN13','PROD-20260102-2998',16,17,NULL,NULL,NULL,NULL,'','',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,0,'2026-01-02 06:45:48','2026-01-02 11:15:48',0,NULL),(31,'ladies fingure','5293276082855','EAN13','PROD-20260102-7586',15,16,19,NULL,14,NULL,'gghj','<p>jgghghg</p>\r\n',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,0,'2026-01-02 13:31:01','2026-01-02 18:01:01',0,NULL),(32,'ladies fingure','4439652899582','EAN13','PROD-20260102-6537',15,16,19,NULL,14,NULL,'yasgg','<p>hsftyh</p>\r\n',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,1,'2026-01-02 13:41:49','2026-01-05 05:07:09',0,'2026-01-02 20:03:52'),(33,'bringal','9627293447407','EAN13','PROD-20260102-4626',16,17,19,NULL,14,NULL,'jshhhx','<p>skjshhshshs</p>\r\n',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,1,'2026-01-02 14:33:23','2026-01-05 05:07:09',0,'2026-01-02 20:03:42'),(34,'ladies fingure','2686915648636','EAN13','PROD-20260102-2671',15,16,19,NULL,14,NULL,'bhgh','<p>khjhj</p>\r\n',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,0,'2026-01-02 14:34:52','2026-01-02 19:04:52',0,NULL),(35,'ladies fingure','5284088494363','EAN13','PROD-20260102-5607',16,17,19,NULL,14,NULL,'aqqqq','<p>aaa</p>\r\n',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,0,'2026-01-02 14:36:09','2026-01-02 19:06:09',0,NULL),(36,'ladies fingure','9371332069906','EAN13','PROD-20260105-1369',15,16,19,NULL,14,NULL,'jhjahghs','<p>hagsfdh</p>\r\n',0.00,NULL,0.00,NULL,0.00,0.00,0.00,0,5,1,10,NULL,0,'2026-01-04 23:58:12','2026-01-05 04:28:12',0,NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_items`
--

DROP TABLE IF EXISTS `purchase_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `purchase_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `purchase_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `purchase_id` (`purchase_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_purchase_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_purchase_items_purchase` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_items`
--

LOCK TABLES `purchase_items` WRITE;
/*!40000 ALTER TABLE `purchase_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchases` (
  `id` int NOT NULL AUTO_INCREMENT,
  `supplier_id` int NOT NULL,
  `reference` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Pending','Ordered','Received') DEFAULT 'Pending',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid` decimal(15,2) NOT NULL DEFAULT '0.00',
  `due` decimal(15,2) DEFAULT '0.00',
  `order_tax` decimal(15,2) DEFAULT '0.00',
  `discount_total` decimal(15,2) DEFAULT '0.00',
  `shipping` decimal(15,2) DEFAULT '0.00',
  `payment_status` enum('Unpaid','Partial','Paid') DEFAULT 'Unpaid',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference` (`reference`),
  KEY `supplier_id` (`supplier_id`),
  CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchases`
--

LOCK TABLES `purchases` WRITE;
/*!40000 ALTER TABLE `purchases` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipping_methods`
--

DROP TABLE IF EXISTS `shipping_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipping_methods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `charge` decimal(10,2) DEFAULT '0.00',
  `min_order_amount` decimal(10,2) DEFAULT '0.00',
  `delivery_time` varchar(50) DEFAULT NULL,
  `type` enum('flat','free','conditional') DEFAULT 'flat',
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipping_methods`
--

LOCK TABLES `shipping_methods` WRITE;
/*!40000 ALTER TABLE `shipping_methods` DISABLE KEYS */;
/*!40000 ALTER TABLE `shipping_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `states`
--

DROP TABLE IF EXISTS `states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `states` (
  `id` int NOT NULL AUTO_INCREMENT,
  `country_id` int NOT NULL,
  `state_name` varchar(150) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` varchar(100) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `display_order` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`),
  CONSTRAINT `states_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `states`
--

LOCK TABLES `states` WRITE;
/*!40000 ALTER TABLE `states` DISABLE KEYS */;
/*!40000 ALTER TABLE `states` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_categories`
--

DROP TABLE IF EXISTS `sub_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sub_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `fk_sub_categories_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_categories`
--

LOCK TABLES `sub_categories` WRITE;
/*!40000 ALTER TABLE `sub_categories` DISABLE KEYS */;
INSERT INTO `sub_categories` VALUES (16,15,'ladies fingure','ladies-fingure',1,'0000-00-00 00:00:00',NULL),(17,16,'juice','juice',1,'0000-00-00 00:00:00',NULL);
/*!40000 ALTER TABLE `sub_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `description` text,
  `contact_person` varchar(100) DEFAULT NULL,
  `payment_terms` varchar(50) DEFAULT NULL,
  `categories` text,
  `status` tinyint DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (14,'yaswanthM','yaashwanth@443gmail.com','6305998104','sdsfsdf','ananthapuram','india','dcfsfsd','yashhh','Net 15','',1,'2025-12-31 02:42:31','2025-12-31 07:12:31');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `units` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `symbol` varchar(10) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
INSERT INTO `units` VALUES (20,'Kg','kilogram',1,'2025-12-31 05:28:29','2025-12-31 05:59:41'),(21,'gms','grams',1,'2025-12-31 06:05:27','2025-12-31 06:05:27'),(22,'ltr','liter',1,'2025-12-31 06:45:40','2025-12-31 06:45:40'),(23,'ml','milli lite',1,'2025-12-31 09:31:13','2025-12-31 09:31:13');
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `villages`
--

DROP TABLE IF EXISTS `villages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `villages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `village_name` varchar(150) NOT NULL,
  `country_id` int NOT NULL,
  `state_id` int NOT NULL,
  `district_id` int NOT NULL,
  `mandal_id` int NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_by` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `deleted_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`),
  KEY `state_id` (`state_id`),
  KEY `district_id` (`district_id`),
  KEY `mandal_id` (`mandal_id`),
  CONSTRAINT `villages_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `villages_ibfk_2` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE,
  CONSTRAINT `villages_ibfk_3` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `villages_ibfk_4` FOREIGN KEY (`mandal_id`) REFERENCES `mandals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `villages`
--

LOCK TABLES `villages` WRITE;
/*!40000 ALTER TABLE `villages` DISABLE KEYS */;
/*!40000 ALTER TABLE `villages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warehouses`
--

DROP TABLE IF EXISTS `warehouses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warehouses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text,
  `status` tinyint(1) DEFAULT '1',
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouses`
--

LOCK TABLES `warehouses` WRITE;
/*!40000 ALTER TABLE `warehouses` DISABLE KEYS */;
INSERT INTO `warehouses` VALUES (7,'yaswanthM','yashhh','6305998105','pranathi12@gmail.com',NULL,'nbmn ',1,1,NULL,NULL,'2025-12-30 16:45:39',NULL,NULL);
/*!40000 ALTER TABLE `warehouses` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-05 11:34:47
