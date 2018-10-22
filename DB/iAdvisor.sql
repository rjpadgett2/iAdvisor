-- MySQL dump 10.13  Distrib 5.7.12, for osx10.9 (x86_64)
--
-- Host: 127.0.0.1    Database: iAdvisor
-- ------------------------------------------------------
-- Server version	5.7.17

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
-- Table structure for table `Course`
--

DROP TABLE IF EXISTS `Course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Course` (
  `class_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `class_num` varchar(10) NOT NULL,
  `class_name` varchar(80) NOT NULL,
  `credits` int(11) NOT NULL,
  `core` int(11) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  PRIMARY KEY (`class_id`,`department_id`),
  KEY `fk_Course_school1_idx` (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Course`
--

LOCK TABLES `Course` WRITE;
/*!40000 ALTER TABLE `Course` DISABLE KEYS */;
INSERT INTO `Course` VALUES (1,'115','Precalculus',3,1,2),(2,'100','Introduction to Psychology',3,1,4),(3,'100','Elementary Statistics and Probability',3,1,3),(4,'126','Programming for non-CS majors',3,1,2),(5,'201','Introduction to Information Science',3,1,1),(6,'311','Information Organization',3,1,1),(7,'314','Statistics for Information Science',3,1,1),(8,'326','Object-Oriented Programming for Information Science',3,1,1),(9,'327','Database Design and Modeling',3,1,1),(10,'335','Teams and Organizations',3,1,1),(11,'346','Technologies, Infrastructures and Architecture',3,1,1),(12,'352','Information User Needs and Assessment',3,1,1),(13,'362','User-Centered Design',3,1,1),(14,'490','Integrative Capstone',3,1,1),(15,'354','Decision-Making for Information Science',3,0,1),(16,'377','Dynamic Web Applications',3,0,1),(17,'414','Advanced Data Science',3,0,1),(18,'447','Data Sources and Manipulation',3,0,1),(19,'462','Introduction to Data Visualization',3,0,1),(20,'466','Technology, Culture, and Society',3,0,1),(21,'408B','Special Topics in Information Science; Design and Humanity Disability and Aging',3,0,0);
/*!40000 ALTER TABLE `Course` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Departments`
--

DROP TABLE IF EXISTS `Departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Departments` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_name` varchar(45) DEFAULT NULL,
  `abbreviation` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`department_id`),
  UNIQUE KEY `school_name_UNIQUE` (`department_name`),
  UNIQUE KEY `abbreviation_UNIQUE` (`abbreviation`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Departments`
--

LOCK TABLES `Departments` WRITE;
/*!40000 ALTER TABLE `Departments` DISABLE KEYS */;
INSERT INTO `Departments` VALUES (1,'Information Studies','INST'),(2,'Mathematics','MATH'),(3,'Statistics','STAT'),(4,'Psychology','PSYC');
/*!40000 ALTER TABLE `Departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Prerequisite`
--

DROP TABLE IF EXISTS `Prerequisite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Prerequisite` (
  `pre_req_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_class_id` int(11) unsigned NOT NULL,
  `course_school_id` int(11) NOT NULL,
  `pre_req_of` int(11) DEFAULT NULL,
  PRIMARY KEY (`pre_req_id`,`course_class_id`),
  KEY `fk_Prerequisite_Course1_idx` (`course_class_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Prerequisite`
--

LOCK TABLES `Prerequisite` WRITE;
/*!40000 ALTER TABLE `Prerequisite` DISABLE KEYS */;
INSERT INTO `Prerequisite` VALUES (1,1,0,3),(2,1,2,4),(3,4,1,9),(4,4,1,9),(5,5,1,6),(6,5,1,7),(7,5,1,8),(8,5,1,9),(9,5,1,10),(10,5,1,11),(11,5,1,12),(12,5,1,13);
/*!40000 ALTER TABLE `Prerequisite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Reccomendations`
--

DROP TABLE IF EXISTS `Reccomendations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Reccomendations` (
  `class_id` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(45) NOT NULL,
  PRIMARY KEY (`class_id`),
  UNIQUE KEY `class_id_UNIQUE` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Reccomendations`
--

LOCK TABLES `Reccomendations` WRITE;
/*!40000 ALTER TABLE `Reccomendations` DISABLE KEYS */;
/*!40000 ALTER TABLE `Reccomendations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Student_Course_Assoc`
--

DROP TABLE IF EXISTS `Student_Course_Assoc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Student_Course_Assoc` (
  `sc_assoc_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `taken` int(11) DEFAULT NULL,
  `semester` varchar(45) DEFAULT NULL,
  `exceptions` int(11) DEFAULT NULL,
  PRIMARY KEY (`sc_assoc_id`),
  KEY `fk_Students_has_Course1_Course1_idx` (`class_id`),
  KEY `fk_Students_has_Course1_Students_idx` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1892 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Student_Course_Assoc`
--

LOCK TABLES `Student_Course_Assoc` WRITE;
/*!40000 ALTER TABLE `Student_Course_Assoc` DISABLE KEYS */;
INSERT INTO `Student_Course_Assoc` VALUES (1871,111111123,2,0,'semester1',0),(1872,111111123,5,0,'semester2',1),(1873,111111123,3,0,'semester2',0),(1874,111111123,4,0,'semester2',0),(1875,111111123,1,0,'semester2',1),(1876,111111123,6,0,'semester2',0),(1877,111111123,7,0,'semester2',0),(1878,111111123,8,0,'semester2',0),(1879,111111123,10,0,'semester2',0),(1880,111111123,9,0,'semester3',0),(1881,111111123,11,0,'semester3',0),(1882,111111123,12,0,'semester3',0),(1883,111111123,13,0,'semester3',0),(1884,111111123,14,0,'semester3',0),(1885,111111123,15,0,'semester3',0),(1886,111111123,16,0,'semester4',0),(1887,111111123,17,0,'semester4',0),(1888,111111123,18,0,'semester4',0),(1889,111111123,19,0,'semester4',0),(1890,111111123,20,0,'semester4',0),(1891,111111123,21,0,'semester5',0);
/*!40000 ALTER TABLE `Student_Course_Assoc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Students`
--

DROP TABLE IF EXISTS `Students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Students` (
  `student_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(45) NOT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=111111126 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Students`
--

LOCK TABLES `Students` WRITE;
/*!40000 ALTER TABLE `Students` DISABLE KEYS */;
INSERT INTO `Students` VALUES (111111120,'jdoe@umd.edu','Doe','John','$2y$10$E8G2NMi4wrrG0LBl2ePCDOjxVaADX16zpZ2hjathPpiQSmQUZZUey','2018-06-06 11:03:14'),(111111121,'asaba@umd.edu','Saba','Azeez','$2y$10$f7JZHSNU/2qjhtQ4PiXZHuLqmdRg02igswMcVPpp0DwNFjPB4RgXC','2018-06-08 17:56:23'),(111111122,'mmonroe@gmail.com','Monroe','Maryland','$2y$10$PD0/x.cN0tv8oSa4yex3cup6YTZsVh8hGxWNBYktT34JdKkVdE6uq','2018-08-09 14:14:29'),(111111123,'dcarter@gmail.com','Carter','Dwayne','$2y$10$nF36jPtn4.5JTOosUob4p.31JzC8wVnm6rlxVjtSTrBWDc4CyE3dy','2018-08-09 14:15:47'),(111111124,'apryor@gmail.com','Pryor','Alex','$2y$10$LbxOCioxihC.Wy1b98ui6eadXxk21azNvAerBEaiZ5ryMkrhV8eQO','2018-08-24 13:48:20'),(111111125,'olimar@gmail.com','Pikpik','Olimar','$2y$10$Q4NydNDhCd1kbEeR0Ke1FOPz2e9vu2t3rZnVkoj4.X9/1FQtgJ8LS','2018-10-18 20:52:56');
/*!40000 ALTER TABLE `Students` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-22  0:35:44
