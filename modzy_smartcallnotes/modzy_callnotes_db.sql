-- MariaDB dump 10.19  Distrib 10.4.19-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: modzy_callnotes
-- ------------------------------------------------------
-- Server version	10.4.19-MariaDB

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
-- Table structure for table `audio_calls`
--

DROP TABLE IF EXISTS `audio_calls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audio_calls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `audio_title` varchar(200) DEFAULT NULL,
  `audio_name` varchar(200) DEFAULT NULL,
  `messages` text DEFAULT NULL,
  `created_time` varchar(200) DEFAULT NULL,
  `user_id` varchar(200) DEFAULT NULL,
  `owner_identity` varchar(500) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `job_id` text DEFAULT NULL,
  `identity` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audio_calls`
--

LOCK TABLES `audio_calls` WRITE;
/*!40000 ALTER TABLE `audio_calls` DISABLE KEYS */;
/*!40000 ALTER TABLE `audio_calls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `name_entity`
--

DROP TABLE IF EXISTS `name_entity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `name_entity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobid` varchar(400) DEFAULT NULL,
  `words` varchar(100) DEFAULT NULL,
  `entity` varchar(100) DEFAULT NULL,
  `entity_desc` varchar(100) DEFAULT NULL,
  `timing` varchar(40) DEFAULT NULL,
  `userid` varchar(40) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=972 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `name_entity`
--

LOCK TABLES `name_entity` WRITE;
/*!40000 ALTER TABLE `name_entity` DISABLE KEYS */;
/*!40000 ALTER TABLE `name_entity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sentiments`
--

DROP TABLE IF EXISTS `sentiments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sentiments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobid` varchar(400) DEFAULT NULL,
  `sentiments` varchar(40) DEFAULT NULL,
  `score` varchar(40) DEFAULT NULL,
  `timing` varchar(40) DEFAULT NULL,
  `userid` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sentiments`
--

LOCK TABLES `sentiments` WRITE;
/*!40000 ALTER TABLE `sentiments` DISABLE KEYS */;
/*!40000 ALTER TABLE `sentiments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modzy_apikey` varchar(300) DEFAULT NULL,
  `revai_apikey` varchar(300) DEFAULT NULL,
  `user_id` varchar(200) DEFAULT NULL,
  `created_time` varchar(200) DEFAULT NULL,
  `owner_identity` varchar(500) DEFAULT NULL,
  `site_url` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `fullname` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `created_time` varchar(200) DEFAULT NULL,
  `owner_identity` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'modzy_callnotes'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-11-29 15:27:43
