-- MySQL dump 10.13  Distrib 5.7.12, for Win32 (AMD64)
--
-- Host: localhost    Database: 4year
-- ------------------------------------------------------
-- Server version	5.6.34-log

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
-- Table structure for table `class`
--

DROP TABLE IF EXISTS `class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class` (
  `class_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_num` varchar(10) NOT NULL,
  `class_name` varchar(80) NOT NULL,
  `prereq1` int(11) DEFAULT NULL,
  `prereq2` int(11) DEFAULT NULL,
  `credits` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `core` int(11) DEFAULT NULL,
  PRIMARY KEY (`class_id`,`school_id`),
  UNIQUE KEY `class_id_UNIQUE` (`class_id`),
  KEY `fk_school_idx` (`school_id`),
  CONSTRAINT `fk_school` FOREIGN KEY (`school_id`) REFERENCES `school` (`school_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class`
--

LOCK TABLES `class` WRITE;
/*!40000 ALTER TABLE `class` DISABLE KEYS */;
INSERT INTO `class` VALUES (1,'115','Precalculus',0,0,3,2,1),(2,'100','Introduction to Psychology',0,0,3,4,1),(3,'100','Elementary Statistics and Probability',0,0,3,3,1),(4,'126','Programming for non-CS majors',1,0,3,1,1),(5,'201','Introduction to Information Science',0,0,3,1,1),(6,'311','Information Organization',5,0,3,1,1),(7,'314','Statistics for Information Science',1,3,3,1,1),(8,'326','Object-Oriented Programming for Information Science',4,0,3,1,1),(9,'327','Database Design and Modeling',4,5,3,1,1),(10,'335','Teams and Organizations',2,5,3,1,1),(11,'346','Technologies, Infrastructures and Architecture',9,8,3,1,1),(12,'352','Information User Needs and Assessment',6,5,3,1,1),(13,'362','User-Centered Design',8,2,3,1,1),(14,'490','Integrative Capstone',12,11,3,1,1),(15,'354','Decision-Making for Information Science',7,0,3,1,0),(16,'377','Dynamic Web Applications',9,0,3,1,0),(17,'414','Advanced Data Science',7,0,3,1,0),(18,'447','Data Sources and Manipulation',8,9,3,1,0),(19,'462','Introduction to Data Visualization',13,0,3,1,0),(20,'466','Technology, Culture, and Society',5,0,3,1,0),(21,'408B','Special Topics in Information Science; Design and Humanity Disability and Aging',0,0,3,1,0);
/*!40000 ALTER TABLE `class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `major`
--

DROP TABLE IF EXISTS `major`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `major` (
  `major_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `school_id` int(11) NOT NULL,
  PRIMARY KEY (`major_id`,`school_id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `major_school_idx` (`school_id`),
  CONSTRAINT `major_school` FOREIGN KEY (`school_id`) REFERENCES `school` (`school_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `major`
--

LOCK TABLES `major` WRITE;
/*!40000 ALTER TABLE `major` DISABLE KEYS */;
INSERT INTO `major` VALUES (1,'Information Science',1);
/*!40000 ALTER TABLE `major` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `major_has_class`
--

DROP TABLE IF EXISTS `major_has_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `major_has_class` (
  `major_major_id` int(11) NOT NULL,
  `major_school_id` int(11) NOT NULL,
  `Class_class_id` int(11) NOT NULL,
  `Class_school_id` int(11) NOT NULL,
  `core_or_elective` varchar(45) NOT NULL,
  PRIMARY KEY (`major_major_id`,`major_school_id`,`Class_class_id`,`Class_school_id`),
  KEY `fk_major_has_Class_major1_idx` (`major_major_id`,`major_school_id`),
  KEY `fk_class_idx` (`Class_school_id`,`Class_class_id`),
  CONSTRAINT `fk_class` FOREIGN KEY (`Class_school_id`, `Class_class_id`) REFERENCES `class` (`school_id`, `class_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_major` FOREIGN KEY (`major_major_id`, `major_school_id`) REFERENCES `major` (`major_id`, `school_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `major_has_class`
--

LOCK TABLES `major_has_class` WRITE;
/*!40000 ALTER TABLE `major_has_class` DISABLE KEYS */;
INSERT INTO `major_has_class` VALUES (1,1,1,2,'core'),(1,1,2,4,'core'),(1,1,3,3,'core'),(1,1,4,1,'core'),(1,1,5,1,'core'),(1,1,6,1,'core'),(1,1,7,1,'core'),(1,1,8,1,'core'),(1,1,9,1,'core'),(1,1,10,1,'core'),(1,1,11,1,'core'),(1,1,12,1,'core'),(1,1,13,1,'core'),(1,1,14,1,'core'),(1,1,15,1,'elective'),(1,1,16,1,'elective'),(1,1,17,1,'elective'),(1,1,18,1,'elective'),(1,1,19,1,'elective'),(1,1,20,1,'elective'),(1,1,21,1,'elective');
/*!40000 ALTER TABLE `major_has_class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `school`
--

DROP TABLE IF EXISTS `school`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `school` (
  `school_id` int(11) NOT NULL AUTO_INCREMENT,
  `school_name` varchar(45) DEFAULT NULL,
  `abbreviation` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`school_id`),
  UNIQUE KEY `school_name_UNIQUE` (`school_name`),
  UNIQUE KEY `abbreviation_UNIQUE` (`abbreviation`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `school`
--

LOCK TABLES `school` WRITE;
/*!40000 ALTER TABLE `school` DISABLE KEYS */;
INSERT INTO `school` VALUES (1,'Information Studies','INST'),(2,'Mathematics','MATH'),(3,'Statistics','STAT'),(4,'Psychology','PSYC');
/*!40000 ALTER TABLE `school` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-12-13 20:32:44
