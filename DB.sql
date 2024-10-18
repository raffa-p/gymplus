-- Progettazione Web 
DROP DATABASE if exists gymplus; 
CREATE DATABASE gymplus; 
USE gymplus; 
-- MySQL dump 10.13  Distrib 5.7.28, for Win64 (x86_64)
--
-- Host: localhost    Database: gymplus
-- ------------------------------------------------------
-- Server version	5.7.28

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
-- Table structure for table `allenamento`
--

DROP TABLE IF EXISTS `allenamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `allenamento` (
  `user` varchar(100) NOT NULL,
  `scheda` int(11) NOT NULL,
  `giorno` int(11) NOT NULL,
  `esercizio` int(11) NOT NULL,
  `serie` int(11) NOT NULL,
  `carico` decimal(10,2) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user`,`giorno`,`scheda`,`esercizio`,`serie`,`timestamp`),
  KEY `allenamento_ibfk_2` (`scheda`),
  KEY `allenamento_ibfk_3` (`esercizio`),
  CONSTRAINT `allenamento_ibfk_1` FOREIGN KEY (`user`) REFERENCES `scheda` (`user`),
  CONSTRAINT `allenamento_ibfk_2` FOREIGN KEY (`scheda`) REFERENCES `scheda` (`id_scheda`),
  CONSTRAINT `allenamento_ibfk_3` FOREIGN KEY (`esercizio`) REFERENCES `esercizi` (`id_esercizio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `allenamento`
--

LOCK TABLES `allenamento` WRITE;
/*!40000 ALTER TABLE `allenamento` DISABLE KEYS */;
/*!40000 ALTER TABLE `allenamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientela`
--

DROP TABLE IF EXISTS `clientela`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientela` (
  `coach` varchar(100) NOT NULL,
  `cliente` varchar(100) NOT NULL,
  PRIMARY KEY (`coach`,`cliente`),
  KEY `clientela_ibfk_2` (`cliente`),
  CONSTRAINT `clientela_ibfk_1` FOREIGN KEY (`coach`) REFERENCES `users` (`username`),
  CONSTRAINT `clientela_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `users` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientela`
--

LOCK TABLES `clientela` WRITE;
/*!40000 ALTER TABLE `clientela` DISABLE KEYS */;
/*!40000 ALTER TABLE `clientela` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dettagli_coach`
--

DROP TABLE IF EXISTS `dettagli_coach`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dettagli_coach` (
  `coach` varchar(100) NOT NULL,
  `codice` varchar(10) NOT NULL,
  PRIMARY KEY (`codice`,`coach`),
  KEY `dettagli_coach_ibfk_1_idx` (`coach`),
  CONSTRAINT `dettagli_coach_ibfk_1` FOREIGN KEY (`coach`) REFERENCES `users` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dettagli_coach`
--

LOCK TABLES `dettagli_coach` WRITE;
/*!40000 ALTER TABLE `dettagli_coach` DISABLE KEYS */;
/*!40000 ALTER TABLE `dettagli_coach` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `esercizi`
--

DROP TABLE IF EXISTS `esercizi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `esercizi` (
  `id_esercizio` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `gruppo_muscolare` varchar(100) NOT NULL,
  PRIMARY KEY (`id_esercizio`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `esercizi`
--

LOCK TABLES `esercizi` WRITE;
/*!40000 ALTER TABLE `esercizi` DISABLE KEYS */;
INSERT INTO `esercizi` VALUES (1,'lat machine',''),(2,'pulley',''),(3,'alzate laterali','spalle'),(4,'panca piana','petto'),(5,'curl bicipit','braccia'),(6,'push down corda','braccia'),(7,'croci ai cavi','petto'),(8,'panca inclinata','petto'),(9,'military press','spalle'),(10,'pressa','gambe'),(11,'squat','gambe');
/*!40000 ALTER TABLE `esercizi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `help_requests`
--

DROP TABLE IF EXISTS `help_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `help_requests` (
  `utente` varchar(60) NOT NULL,
  `timestamp_` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `contenuto` varchar(500) NOT NULL,
  PRIMARY KEY (`utente`,`timestamp_`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `help_requests`
--

LOCK TABLES `help_requests` WRITE;
/*!40000 ALTER TABLE `help_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `help_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_allenamento`
--

DROP TABLE IF EXISTS `post_allenamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_allenamento` (
  `coach` varchar(100) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user` varchar(100) NOT NULL,
  `commenti` varchar(300) DEFAULT NULL,
  `visto` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`timestamp`,`user`,`coach`),
  KEY `user` (`user`),
  KEY `post_allenamento_ibfk_idx` (`coach`),
  CONSTRAINT `post_allenamento_ibfk_1` FOREIGN KEY (`user`) REFERENCES `allenamento` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_allenamento`
--

LOCK TABLES `post_allenamento` WRITE;
/*!40000 ALTER TABLE `post_allenamento` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_allenamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scheda`
--

DROP TABLE IF EXISTS `scheda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scheda` (
  `id_scheda` int(11) NOT NULL AUTO_INCREMENT,
  `coach` varchar(100) NOT NULL,
  `user` varchar(100) NOT NULL,
  `data_rilascio` date NOT NULL,
  `annotazioni` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id_scheda`),
  KEY `fk_scheda_coach` (`coach`),
  KEY `scheda_ibfk_1` (`user`),
  CONSTRAINT `fk_scheda_coach` FOREIGN KEY (`coach`) REFERENCES `users` (`username`),
  CONSTRAINT `scheda_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scheda`
--

LOCK TABLES `scheda` WRITE;
/*!40000 ALTER TABLE `scheda` DISABLE KEYS */;
/*!40000 ALTER TABLE `scheda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scheda_dettaglio`
--

DROP TABLE IF EXISTS `scheda_dettaglio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scheda_dettaglio` (
  `id_scheda` int(11) DEFAULT NULL,
  `id_esercizio` int(11) DEFAULT NULL,
  `n_serie` int(11) NOT NULL,
  `n_reps` int(11) NOT NULL,
  `giorno` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `id_scheda` (`id_scheda`),
  KEY `id_esercizio` (`id_esercizio`),
  CONSTRAINT `scheda_dettaglio_ibfk_1` FOREIGN KEY (`id_scheda`) REFERENCES `scheda` (`id_scheda`),
  CONSTRAINT `scheda_dettaglio_ibfk_2` FOREIGN KEY (`id_esercizio`) REFERENCES `esercizi` (`id_esercizio`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scheda_dettaglio`
--

LOCK TABLES `scheda_dettaglio` WRITE;
/*!40000 ALTER TABLE `scheda_dettaglio` DISABLE KEYS */;
/*!40000 ALTER TABLE `scheda_dettaglio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `to_do_list`
--

DROP TABLE IF EXISTS `to_do_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `to_do_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(60) DEFAULT NULL,
  `task` text,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  CONSTRAINT `to_do_list_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `to_do_list`
--

LOCK TABLES `to_do_list` WRITE;
/*!40000 ALTER TABLE `to_do_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `to_do_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `username` varchar(60) NOT NULL,
  `password` varchar(180) NOT NULL,
  `coach` tinyint(1) NOT NULL DEFAULT '0',
  `nome` varchar(45) NOT NULL,
  `cognome` varchar(45) NOT NULL,
  `data_nascita` date DEFAULT NULL,
  `telefono` varchar(13) DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-09-08 23:59:09
