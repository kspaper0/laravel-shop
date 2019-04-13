-- MySQL dump 10.13  Distrib 5.7.22, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: laravel-shop
-- ------------------------------------------------------
-- Server version	5.7.22-0ubuntu18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `admin_menu`
--

LOCK TABLES `admin_menu` WRITE;
/*!40000 ALTER TABLE `admin_menu` DISABLE KEYS */;
INSERT INTO `admin_menu` VALUES (1,0,1,'Index','fa-bar-chart','/',NULL,NULL),(2,0,7,'Admin','fa-tasks','',NULL,'2019-04-13 07:05:01'),(3,2,8,'Users','fa-users','auth/users',NULL,'2019-04-13 07:05:01'),(4,2,9,'Roles','fa-user','auth/roles',NULL,'2019-04-13 07:05:01'),(5,2,10,'Permission','fa-ban','auth/permissions',NULL,'2019-04-13 07:05:01'),(6,2,11,'Menu','fa-bars','auth/menu',NULL,'2019-04-13 07:05:01'),(7,2,12,'Operation log','fa-history','auth/logs',NULL,'2019-04-13 07:05:01'),(8,0,2,'User Management','fa-users','/users','2019-03-20 09:30:06','2019-03-20 09:30:52'),(9,0,4,'Product Management','fa-cubes','/products','2019-03-20 12:12:59','2019-04-13 07:05:01'),(10,0,5,'Order Management','fa-usd','/orders','2019-04-04 08:17:47','2019-04-13 07:05:01'),(11,0,6,'Coupon Management','fa-tags','/coupon_codes','2019-04-07 10:16:05','2019-04-13 07:05:01'),(12,0,3,'Category Management','fa-bars','/categories','2019-04-13 07:04:32','2019-04-13 07:05:01');
/*!40000 ALTER TABLE `admin_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_permissions`
--

LOCK TABLES `admin_permissions` WRITE;
/*!40000 ALTER TABLE `admin_permissions` DISABLE KEYS */;
INSERT INTO `admin_permissions` VALUES (1,'All permission','*','','*',NULL,NULL),(2,'Dashboard','dashboard','GET','/',NULL,NULL),(3,'Login','auth.login','','/auth/login\r\n/auth/logout',NULL,NULL),(4,'User setting','auth.setting','GET,PUT','/auth/setting',NULL,NULL),(5,'Auth management','auth.management','','/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs',NULL,NULL),(6,'User management','users','','/users*','2019-03-20 09:48:26','2019-03-20 09:48:26'),(7,'Product Management','products','','/products*','2019-04-10 12:38:56','2019-04-10 12:38:56'),(8,'Coupon Management','coupon_codes','','/coupon_codes*','2019-04-10 12:40:00','2019-04-10 12:40:00'),(9,'Order Management','orders','','/orders*','2019-04-10 12:40:36','2019-04-10 12:40:36');
/*!40000 ALTER TABLE `admin_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_menu`
--

LOCK TABLES `admin_role_menu` WRITE;
/*!40000 ALTER TABLE `admin_role_menu` DISABLE KEYS */;
INSERT INTO `admin_role_menu` VALUES (1,2,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_permissions`
--

LOCK TABLES `admin_role_permissions` WRITE;
/*!40000 ALTER TABLE `admin_role_permissions` DISABLE KEYS */;
INSERT INTO `admin_role_permissions` VALUES (1,1,NULL,NULL),(2,2,NULL,NULL),(2,3,NULL,NULL),(2,4,NULL,NULL),(2,6,NULL,NULL),(2,7,NULL,NULL),(2,8,NULL,NULL),(2,9,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_role_users`
--

LOCK TABLES `admin_role_users` WRITE;
/*!40000 ALTER TABLE `admin_role_users` DISABLE KEYS */;
INSERT INTO `admin_role_users` VALUES (1,1,NULL,NULL),(2,2,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_roles`
--

LOCK TABLES `admin_roles` WRITE;
/*!40000 ALTER TABLE `admin_roles` DISABLE KEYS */;
INSERT INTO `admin_roles` VALUES (1,'Administrator','administrator','2019-03-20 08:24:51','2019-03-20 08:24:51'),(2,'Operator','operator','2019-03-20 09:51:11','2019-03-20 09:51:11');
/*!40000 ALTER TABLE `admin_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_user_permissions`
--

LOCK TABLES `admin_user_permissions` WRITE;
/*!40000 ALTER TABLE `admin_user_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_user_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'admin','$2y$10$IMuN/qvM2Ia4cMlDLNAIHemvQ1Rl4IVrK9KZI0c0Ou673c0XWD6y.','Administrator',NULL,'IqcDtgfNbq0Nbi7KqIPlPLlnqg4Yu5TMRRwscoV0SczmRKXiIfsD2FA9srzc','2019-03-20 08:24:51','2019-03-20 08:24:51'),(2,'operator','$2y$10$OSJJezM2yPv9X5lqX7mqZeXZrxy3xlkJjww03ovUnuwbL4ZPn22mm','Operator','images/bruce.jpg','ZLnRyEEDME5wBpQJqVLQrJZFVUZQ6WFJP8T4f1PEklx3tghWjWMHYguf1GHN','2019-03-20 09:53:54','2019-03-20 10:05:32');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-04-13  7:44:47
