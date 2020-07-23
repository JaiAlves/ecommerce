-- MariaDB dump 10.17  Distrib 10.4.11-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: db_ecommerce
-- ------------------------------------------------------
-- Server version	10.4.11-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tb_addresses`
--

DROP TABLE IF EXISTS `tb_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_addresses` (
  `idaddress` int(11) NOT NULL AUTO_INCREMENT,
  `idperson` int(11) NOT NULL,
  `desaddress` varchar(128) NOT NULL,
  `descomplement` varchar(32) DEFAULT NULL,
  `descity` varchar(32) NOT NULL,
  `desstate` varchar(32) NOT NULL,
  `descountry` varchar(32) NOT NULL,
  `nrzipcode` int(11) NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idaddress`),
  KEY `fk_addresses_persons_idx` (`idperson`),
  CONSTRAINT `fk_addresses_persons` FOREIGN KEY (`idperson`) REFERENCES `tb_persons` (`idperson`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_addresses`
--

LOCK TABLES `tb_addresses` WRITE;
/*!40000 ALTER TABLE `tb_addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_carts`
--

DROP TABLE IF EXISTS `tb_carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_carts` (
  `idcart` int(11) NOT NULL AUTO_INCREMENT,
  `dessessionid` varchar(64) NOT NULL,
  `iduser` int(11) DEFAULT NULL,
  `deszipcode` char(8) DEFAULT NULL,
  `vlfreight` decimal(10,2) DEFAULT NULL,
  `nrdays` int(11) DEFAULT NULL,
  `dtregister` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idcart`),
  KEY `FK_carts_users_idx` (`iduser`),
  CONSTRAINT `fk_carts_users` FOREIGN KEY (`iduser`) REFERENCES `tb_users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_carts`
--

LOCK TABLES `tb_carts` WRITE;
/*!40000 ALTER TABLE `tb_carts` DISABLE KEYS */;
INSERT INTO `tb_carts` VALUES (2,'a01m0vllmg69r7ihb85shfgtff',NULL,'01311904',22.50,3,'2020-07-19 17:47:36'),(3,'47fttd4pvulcdi1gc3mt9jkr9l',NULL,NULL,NULL,NULL,'2020-07-20 23:09:20');
/*!40000 ALTER TABLE `tb_carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_cartsproducts`
--

DROP TABLE IF EXISTS `tb_cartsproducts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_cartsproducts` (
  `idcartproduct` int(11) NOT NULL AUTO_INCREMENT,
  `idcart` int(11) NOT NULL,
  `idproduct` int(11) NOT NULL,
  `dtremoved` datetime DEFAULT NULL,
  `dtregister` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idcartproduct`),
  KEY `FK_cartsproducts_carts_idx` (`idcart`),
  KEY `FK_cartsproducts_products_idx` (`idproduct`),
  CONSTRAINT `fk_cartsproducts_carts` FOREIGN KEY (`idcart`) REFERENCES `tb_carts` (`idcart`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_cartsproducts_products` FOREIGN KEY (`idproduct`) REFERENCES `tb_products` (`idproduct`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_cartsproducts`
--

LOCK TABLES `tb_cartsproducts` WRITE;
/*!40000 ALTER TABLE `tb_cartsproducts` DISABLE KEYS */;
INSERT INTO `tb_cartsproducts` VALUES (1,2,35,'2020-07-19 16:25:07','2020-07-19 19:22:25'),(2,2,35,'2020-07-19 16:25:07','2020-07-19 19:22:31'),(3,2,35,'2020-07-19 16:25:07','2020-07-19 19:22:36'),(4,2,43,'2020-07-19 16:26:54','2020-07-19 19:25:46'),(5,2,43,'2020-07-19 16:26:54','2020-07-19 19:25:57'),(6,2,37,'2020-07-19 16:41:50','2020-07-19 19:27:33'),(7,2,28,'2020-07-19 16:41:47','2020-07-19 19:27:53'),(8,2,37,'2020-07-19 16:41:50','2020-07-19 19:28:12'),(9,2,32,'2020-07-19 16:28:28','2020-07-19 19:28:25'),(10,2,33,'2020-07-19 16:35:32','2020-07-19 19:28:42'),(11,2,33,'2020-07-19 17:59:51','2020-07-19 19:42:09'),(12,2,33,'2020-07-19 19:14:33','2020-07-19 19:42:09'),(13,2,33,'2020-07-19 19:15:13','2020-07-19 22:12:14'),(14,2,33,'2020-07-19 19:15:13','2020-07-19 22:12:48'),(15,2,33,'2020-07-19 19:15:13','2020-07-19 22:13:08'),(16,2,23,'2020-07-19 19:20:01','2020-07-19 22:15:35'),(17,2,23,'2020-07-19 19:20:21','2020-07-19 22:15:44'),(18,2,23,'2020-07-19 19:20:21','2020-07-19 22:17:51'),(19,2,23,NULL,'2020-07-19 22:19:16'),(20,2,23,NULL,'2020-07-19 22:27:19'),(21,2,35,'2020-07-19 19:36:30','2020-07-19 22:28:36'),(22,2,35,'2020-07-19 19:36:30','2020-07-19 22:28:46'),(23,2,35,'2020-07-19 19:36:30','2020-07-19 22:29:01'),(24,2,32,'2020-07-19 19:32:33','2020-07-19 22:31:19'),(25,2,32,'2020-07-19 19:32:33','2020-07-19 22:31:19'),(26,2,32,'2020-07-19 19:32:33','2020-07-19 22:31:19'),(27,2,32,'2020-07-19 19:32:33','2020-07-19 22:31:19'),(28,2,32,'2020-07-19 19:32:33','2020-07-19 22:31:19'),(29,2,32,'2020-07-19 19:32:33','2020-07-19 22:31:19'),(30,2,32,'2020-07-19 19:32:33','2020-07-19 22:31:19'),(31,2,32,'2020-07-19 19:32:33','2020-07-19 22:31:19'),(32,2,32,'2020-07-19 19:32:33','2020-07-19 22:31:19'),(33,2,32,'2020-07-19 19:32:33','2020-07-19 22:31:19'),(34,2,23,NULL,'2020-07-19 22:33:24'),(35,3,28,NULL,'2020-07-20 23:32:24'),(36,3,28,NULL,'2020-07-20 23:32:37');
/*!40000 ALTER TABLE `tb_cartsproducts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_categories`
--

DROP TABLE IF EXISTS `tb_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_categories` (
  `idcategory` int(11) NOT NULL AUTO_INCREMENT,
  `descategory` varchar(32) NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idcategory`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_categories`
--

LOCK TABLES `tb_categories` WRITE;
/*!40000 ALTER TABLE `tb_categories` DISABLE KEYS */;
INSERT INTO `tb_categories` VALUES (3,'Android','2020-04-05 21:05:07'),(4,'Apple','2020-04-05 21:05:18'),(5,'Motorola','2020-04-05 21:05:27'),(6,'Samsung','2020-04-05 21:05:36');
/*!40000 ALTER TABLE `tb_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_orders`
--

DROP TABLE IF EXISTS `tb_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_orders` (
  `idorder` int(11) NOT NULL AUTO_INCREMENT,
  `idcart` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `idstatus` int(11) NOT NULL,
  `vltotal` decimal(10,2) NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idorder`),
  KEY `FK_orders_carts_idx` (`idcart`),
  KEY `FK_orders_users_idx` (`iduser`),
  KEY `fk_orders_ordersstatus_idx` (`idstatus`),
  CONSTRAINT `fk_orders_carts` FOREIGN KEY (`idcart`) REFERENCES `tb_carts` (`idcart`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_ordersstatus` FOREIGN KEY (`idstatus`) REFERENCES `tb_ordersstatus` (`idstatus`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_users` FOREIGN KEY (`iduser`) REFERENCES `tb_users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_orders`
--

LOCK TABLES `tb_orders` WRITE;
/*!40000 ALTER TABLE `tb_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_ordersstatus`
--

DROP TABLE IF EXISTS `tb_ordersstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_ordersstatus` (
  `idstatus` int(11) NOT NULL AUTO_INCREMENT,
  `desstatus` varchar(32) NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idstatus`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_ordersstatus`
--

LOCK TABLES `tb_ordersstatus` WRITE;
/*!40000 ALTER TABLE `tb_ordersstatus` DISABLE KEYS */;
INSERT INTO `tb_ordersstatus` VALUES (1,'Em Aberto','2017-03-13 06:00:00'),(2,'Aguardando Pagamento','2017-03-13 06:00:00'),(3,'Pago','2017-03-13 06:00:00'),(4,'Entregue','2017-03-13 06:00:00');
/*!40000 ALTER TABLE `tb_ordersstatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_persons`
--

DROP TABLE IF EXISTS `tb_persons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_persons` (
  `idperson` int(11) NOT NULL AUTO_INCREMENT,
  `desperson` varchar(64) NOT NULL,
  `desemail` varchar(128) DEFAULT NULL,
  `nrphone` bigint(20) DEFAULT NULL,
  `dtregister` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idperson`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_persons`
--

LOCK TABLES `tb_persons` WRITE;
/*!40000 ALTER TABLE `tb_persons` DISABLE KEYS */;
INSERT INTO `tb_persons` VALUES (1,'Administrador','admin@hcode.com.br',2147483647,'2017-03-01 06:00:00'),(19,'Jai','jailsonalve@gmail.com',99999,'2020-04-01 23:03:58');
/*!40000 ALTER TABLE `tb_persons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_products`
--

DROP TABLE IF EXISTS `tb_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_products` (
  `idproduct` int(11) NOT NULL AUTO_INCREMENT,
  `desproduct` varchar(64) NOT NULL,
  `vlprice` decimal(10,2) NOT NULL,
  `vlwidth` decimal(10,2) NOT NULL,
  `vlheight` decimal(10,2) NOT NULL,
  `vllength` decimal(10,2) NOT NULL,
  `vlweight` decimal(10,4) NOT NULL,
  `desurl` varchar(128) NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idproduct`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_products`
--

LOCK TABLES `tb_products` WRITE;
/*!40000 ALTER TABLE `tb_products` DISABLE KEYS */;
INSERT INTO `tb_products` VALUES (22,'Smartphone  Samsung G5 Plus',1135.23,15.20,7.40,0.70,0.1600,'22','2020-06-14 19:46:55'),(23,'Smartphone Samsung Play',1887.78,14.10,0.90,1.16,0.1300,'23','2020-06-14 19:46:55'),(24,'Smartphone Samsung Galaxy J5 Pro',1299.00,14.60,7.10,0.80,0.1600,'24','2020-06-14 19:46:55'),(25,'Smartphone Samsung Galaxy J7 Prime',1149.00,15.10,7.50,0.80,0.1600,'25','2020-06-14 19:46:55'),(26,'Smartphone Samsung Galaxy J3 Dual',679.90,14.20,7.10,0.70,0.1400,'26','2020-06-14 19:46:55'),(27,'iPhone XR Apple Preto 64GB, Tela Retina LCD de 6,1”, iOS 12, Câm',3399.00,15.20,7.10,0.80,0.1600,'27','2020-07-18 14:39:50'),(28,'Apple iPhone 7 Tela LCD Retina HD 4,7” iOS 13 32 GB - Preto Matt',1799.00,15.20,7.10,0.80,0.1600,'28','2020-07-18 14:49:50'),(29,'iPhone 8 Plus Apple com 128GB, Tela Retina HD de 5,5”, iOS 11, D',3299.00,15.20,7.10,0.80,0.1600,'29','2020-07-18 14:52:09'),(30,'iPhone XR Apple Branco 128GB, Tela Retina LCD de 6,1”, iOS 12, C',3999.00,15.20,7.10,0.80,0.1600,'30','2020-07-18 14:54:21'),(31,'iPhone 8 Plus Apple com 128GB, Tela Retina HD de 5,5”',3339.00,15.20,7.10,0.80,0.1600,'31','2020-07-18 14:56:13'),(32,'iPhone 6s Apple com 3D Touch, iOS 13, Sensor Touch ID, Câmera iS',1599.00,15.20,7.10,0.80,0.1600,'32','2020-07-18 14:58:37'),(33,'iPhone 11 Apple com 128GB, Tela Retina HD de 6,1”, iOS 13, Dupla',4699.00,15.20,7.10,0.80,0.1600,'33','2020-07-18 15:00:47'),(34,'iPhone XR Apple Branco 64GB, Tela Retina LCD de 6,1”, iOS 12, Câ',3699.00,15.20,7.10,0.80,0.1600,'34','2020-07-18 15:03:12'),(35,'iPhone 11 Apple com 128GB, Tela Retina HD de 6,1”, iOS 13, Dupla',4809.00,15.20,7.10,0.80,0.1600,'35','2020-07-18 15:05:08'),(36,'iPhone 8 Plus Apple com 128GB, Tela Retina HD de 5,5”, iOS 11, D',3699.00,15.20,7.10,0.80,0.1600,'36','2020-07-18 15:07:15'),(37,'iPhone 11 Pro Apple com 256GB, Tela Retina HD de 5,8”, iOS 13, T',6799.00,15.20,7.10,0.80,0.1600,'37','2020-07-18 15:11:39'),(43,'motorola one fusion+ - Branco Prisma',2148.99,15.20,7.10,0.80,0.1600,'43','2020-07-18 19:08:28'),(44,'motorola edge - Solar Black',5050.99,15.20,7.10,0.80,0.1600,'44','2020-07-18 19:11:11'),(45,'Moto G8 Power Lite - Azul Navy',1249.00,15.20,7.10,0.80,0.1600,'45','2020-07-18 19:19:21'),(46,'Moto e6s - Azul Navy',939.00,15.20,7.10,0.80,0.1600,'46','2020-07-18 19:22:28'),(48,'Motorola razr - Black',8199.00,15.20,7.10,0.80,0.1600,'48','2020-07-18 19:26:38');
/*!40000 ALTER TABLE `tb_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_productscategories`
--

DROP TABLE IF EXISTS `tb_productscategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_productscategories` (
  `idcategory` int(11) NOT NULL,
  `idproduct` int(11) NOT NULL,
  PRIMARY KEY (`idcategory`,`idproduct`),
  KEY `fk_productscategories_products_idx` (`idproduct`),
  CONSTRAINT `fk_productscategories_categories` FOREIGN KEY (`idcategory`) REFERENCES `tb_categories` (`idcategory`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_productscategories_products` FOREIGN KEY (`idproduct`) REFERENCES `tb_products` (`idproduct`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_productscategories`
--

LOCK TABLES `tb_productscategories` WRITE;
/*!40000 ALTER TABLE `tb_productscategories` DISABLE KEYS */;
INSERT INTO `tb_productscategories` VALUES (4,27),(4,28),(4,29),(4,30),(4,31),(4,32),(4,33),(4,34),(4,35),(4,36),(4,37),(5,43),(5,44),(5,45),(5,46),(5,48),(6,22),(6,23),(6,24),(6,25),(6,26);
/*!40000 ALTER TABLE `tb_productscategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_users`
--

DROP TABLE IF EXISTS `tb_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_users` (
  `iduser` int(11) NOT NULL AUTO_INCREMENT,
  `idperson` int(11) NOT NULL,
  `deslogin` varchar(64) NOT NULL,
  `despassword` varchar(256) NOT NULL,
  `inadmin` tinyint(4) NOT NULL DEFAULT 0,
  `dtregister` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`iduser`),
  KEY `FK_users_persons_idx` (`idperson`),
  CONSTRAINT `fk_users_persons` FOREIGN KEY (`idperson`) REFERENCES `tb_persons` (`idperson`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_users`
--

LOCK TABLES `tb_users` WRITE;
/*!40000 ALTER TABLE `tb_users` DISABLE KEYS */;
INSERT INTO `tb_users` VALUES (1,1,'admin','$2y$12$YlooCyNvyTji8bPRcrfNfOKnVMmZA9ViM2A3IpFjmrpIbp5ovNmga',1,'2017-03-13 06:00:00'),(19,19,'jai','$2y$12$SVJhhhtM/BtSqMJqiGijN.iXuqsZGXQXFCuPx8ruLqhZsR8tYdvFG',1,'2020-04-01 23:03:58');
/*!40000 ALTER TABLE `tb_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_userslogs`
--

DROP TABLE IF EXISTS `tb_userslogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_userslogs` (
  `idlog` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `deslog` varchar(128) NOT NULL,
  `desip` varchar(45) NOT NULL,
  `desuseragent` varchar(128) NOT NULL,
  `dessessionid` varchar(64) NOT NULL,
  `desurl` varchar(128) NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idlog`),
  KEY `fk_userslogs_users_idx` (`iduser`),
  CONSTRAINT `fk_userslogs_users` FOREIGN KEY (`iduser`) REFERENCES `tb_users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_userslogs`
--

LOCK TABLES `tb_userslogs` WRITE;
/*!40000 ALTER TABLE `tb_userslogs` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_userslogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_userspasswordsrecoveries`
--

DROP TABLE IF EXISTS `tb_userspasswordsrecoveries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_userspasswordsrecoveries` (
  `idrecovery` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `desip` varchar(45) NOT NULL,
  `dtrecovery` datetime DEFAULT NULL,
  `dtregister` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idrecovery`),
  KEY `fk_userspasswordsrecoveries_users_idx` (`iduser`),
  CONSTRAINT `fk_userspasswordsrecoveries_users` FOREIGN KEY (`iduser`) REFERENCES `tb_users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_userspasswordsrecoveries`
--

LOCK TABLES `tb_userspasswordsrecoveries` WRITE;
/*!40000 ALTER TABLE `tb_userspasswordsrecoveries` DISABLE KEYS */;
INSERT INTO `tb_userspasswordsrecoveries` VALUES (37,19,'127.0.0.1','2020-04-03 21:05:21','2020-04-04 00:05:04'),(38,19,'127.0.0.1','2020-04-03 21:08:47','2020-04-04 00:07:32'),(39,19,'127.0.0.1',NULL,'2020-04-12 18:14:49'),(40,19,'127.0.0.1',NULL,'2020-04-12 18:15:15'),(41,19,'127.0.0.1',NULL,'2020-04-12 18:15:31'),(42,19,'127.0.0.1',NULL,'2020-04-12 18:21:15'),(43,19,'127.0.0.1',NULL,'2020-04-12 18:22:17'),(44,19,'127.0.0.1',NULL,'2020-04-13 16:37:14'),(45,19,'127.0.0.1',NULL,'2020-04-13 16:38:10'),(46,19,'127.0.0.1',NULL,'2020-04-13 16:39:21'),(47,19,'127.0.0.1',NULL,'2020-04-13 16:40:15'),(48,19,'127.0.0.1',NULL,'2020-04-13 17:03:05'),(49,19,'127.0.0.1',NULL,'2020-04-25 13:45:12'),(50,19,'127.0.0.1',NULL,'2020-04-25 13:55:07'),(51,19,'127.0.0.1',NULL,'2020-04-25 13:57:18'),(52,19,'127.0.0.1',NULL,'2020-04-25 15:05:43');
/*!40000 ALTER TABLE `tb_userspasswordsrecoveries` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-07-22 22:44:47
