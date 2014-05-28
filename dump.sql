-- MySQL dump 10.13  Distrib 5.5.37, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: viridis4
-- ------------------------------------------------------
-- Server version	5.5.37-1

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
-- Table structure for table `characters`
--

DROP TABLE IF EXISTS `characters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `characters` (
  `accountID` int(11) DEFAULT NULL,
  `charID` int(11) NOT NULL AUTO_INCREMENT,
  `charName` varchar(30) DEFAULT NULL,
  `cooldown` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `charClassID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  PRIMARY KEY (`charID`),
  KEY `accountID` (`accountID`),
  KEY `userID` (`userID`),
  CONSTRAINT `characters_ibfk_1` FOREIGN KEY (`accountID`) REFERENCES `gameaccounts` (`accountID`),
  CONSTRAINT `characters_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `useraccount` (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cooldowns`
--

DROP TABLE IF EXISTS `cooldowns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cooldowns` (
  `cooldownID` int(11) NOT NULL AUTO_INCREMENT,
  `eventID` int(11) NOT NULL,
  `accountID` int(11) DEFAULT NULL,
  `charID` int(11) DEFAULT NULL,
  `endDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `eventTypeID` int(11) NOT NULL,
  PRIMARY KEY (`cooldownID`),
  KEY `eventID` (`eventID`),
  KEY `accountID` (`accountID`),
  KEY `charID` (`charID`),
  KEY `eventTypeID` (`eventTypeID`),
  CONSTRAINT `cooldowns_ibfk_5` FOREIGN KEY (`eventTypeID`) REFERENCES `eventTypes` (`eventTypeID`),
  CONSTRAINT `cooldowns_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `events` (`eventID`),
  CONSTRAINT `cooldowns_ibfk_2` FOREIGN KEY (`accountID`) REFERENCES `gameaccounts` (`accountID`),
  CONSTRAINT `cooldowns_ibfk_3` FOREIGN KEY (`charID`) REFERENCES `characters` (`charID`),
  CONSTRAINT `cooldowns_ibfk_4` FOREIGN KEY (`eventTypeID`) REFERENCES `eventTypes` (`eventTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `drops`
--

DROP TABLE IF EXISTS `drops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drops` (
  `eventID` int(11) NOT NULL,
  `dropID` int(11) NOT NULL AUTO_INCREMENT,
  `holdingUserID` int(11) DEFAULT NULL,
  `sold` tinyint(1) DEFAULT '0',
  `soldPrice` int(11) DEFAULT NULL,
  `itemID` int(11) NOT NULL,
  PRIMARY KEY (`dropID`),
  KEY `eventID` (`eventID`),
  KEY `holdingUserID` (`holdingUserID`),
  KEY `itemID` (`itemID`),
  CONSTRAINT `drops_ibfk_3` FOREIGN KEY (`itemID`) REFERENCES `items` (`itemID`),
  CONSTRAINT `drops_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `events` (`eventID`),
  CONSTRAINT `drops_ibfk_2` FOREIGN KEY (`holdingUserID`) REFERENCES `useraccount` (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `eventTypes`
--

DROP TABLE IF EXISTS `eventTypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eventTypes` (
  `eventTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `eventName` varchar(50) NOT NULL,
  `characterCooldown` int(11) NOT NULL DEFAULT '0',
  `accountCooldown` int(11) NOT NULL DEFAULT '0',
  `numSlots` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`eventTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `eventID` int(11) NOT NULL AUTO_INCREMENT,
  `eventTypeID` int(11) DEFAULT NULL,
  `startDate` datetime NOT NULL,
  `completeDate` datetime DEFAULT NULL,
  `eventState` int(11) NOT NULL DEFAULT '0',
  `recurringEvent` tinyint(1) NOT NULL DEFAULT '0',
  `dayOfWeek` int(11) DEFAULT NULL,
  `hourOfDay` int(11) DEFAULT NULL,
  PRIMARY KEY (`eventID`),
  KEY `eventTypeID` (`eventTypeID`),
  CONSTRAINT `events_ibfk_1` FOREIGN KEY (`eventTypeID`) REFERENCES `eventTypes` (`eventTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `featuredlinks`
--

DROP TABLE IF EXISTS `featuredlinks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `featuredlinks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `location` varchar(255) NOT NULL,
  `weight` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gameaccounts`
--

DROP TABLE IF EXISTS `gameaccounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gameaccounts` (
  `accountID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `cooldown` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`accountID`),
  KEY `userID` (`userID`),
  CONSTRAINT `gameaccounts_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `useraccount` (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `itemID` int(11) NOT NULL,
  `aegisName` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`itemID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(10) NOT NULL,
  `query` mediumtext NOT NULL,
  `binds` mediumtext,
  `result` varchar(10) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5063 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `navbarlinks`
--

DROP TABLE IF EXISTS `navbarlinks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `navbarlinks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `location` varchar(255) NOT NULL,
  `visibility` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `participants`
--

DROP TABLE IF EXISTS `participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `participants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `runID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `paidOut` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `runID` (`runID`,`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=1347 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `slots`
--

DROP TABLE IF EXISTS `slots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `slots` (
  `eventID` int(11) NOT NULL,
  `slotID` int(11) NOT NULL AUTO_INCREMENT,
  `slotClassID` int(11) DEFAULT NULL,
  `taken` tinyint(1) NOT NULL DEFAULT '0',
  `takenUserID` int(11) DEFAULT NULL,
  `takenCharID` int(11) DEFAULT NULL,
  PRIMARY KEY (`slotID`),
  KEY `eventID` (`eventID`),
  KEY `takenUserID` (`takenUserID`),
  KEY `takenCharID` (`takenCharID`),
  CONSTRAINT `slots_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `events` (`eventID`),
  CONSTRAINT `slots_ibfk_2` FOREIGN KEY (`takenUserID`) REFERENCES `useraccount` (`userID`),
  CONSTRAINT `slots_ibfk_3` FOREIGN KEY (`takenCharID`) REFERENCES `characters` (`charID`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usefullinks`
--

DROP TABLE IF EXISTS `usefullinks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usefullinks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `location` varchar(255) NOT NULL,
  `weight` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `useraccount`
--

DROP TABLE IF EXISTS `useraccount`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `useraccount` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `userLogin` varchar(20) NOT NULL,
  `userPassword` varchar(50) NOT NULL,
  `roleLevel` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mailChar` varchar(50) DEFAULT NULL,
  `forumAccount` varchar(50) NOT NULL,
  `payout` int(11) DEFAULT '0',
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `mailname` varchar(30) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(32) NOT NULL DEFAULT 'zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz',
  `permissions` int(5) DEFAULT '0',
  `forumname` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-05-28 10:24:07
