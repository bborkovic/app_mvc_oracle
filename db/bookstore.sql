-- MySQL dump 10.13  Distrib 5.6.33, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: bookstore
-- ------------------------------------------------------
-- Server version	5.6.33-0ubuntu0.14.04.1

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
-- Table structure for table `authors`
--

DROP TABLE IF EXISTS `authors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) DEFAULT NULL,
  `about` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authors`
--

LOCK TABLES `authors` WRITE;
/*!40000 ALTER TABLE `authors` DISABLE KEYS */;
INSERT INTO `authors` VALUES (1,'Tom','Kyte',NULL),(2,'Darl','Kuhn',NULL),(3,'Steve','Prettyman','Steve Prettyman is a college instructor on PHP programming, web development and related.  He is and has been a practicing web developer and is a book author.'),(4,'Michael','Hartl',NULL),(5,'Tom','Butler',NULL),(6,'Kevin','Yank',NULL),(7,'Arup','Nanda',NULL),(8,'Andre','Ben-Hamou',NULL);
/*!40000 ALTER TABLE `authors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `short_info` varchar(200) DEFAULT NULL,
  `about_book` text,
  `about_authors` text,
  `book_photo` varchar(200) DEFAULT NULL,
  `publisher_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `books_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;
INSERT INTO `books` VALUES (2,3,'Learn PHP 7','Object Oriented Modular Programming using HTML5, CSS3, JavaScript, XML, JSON, and MySQl','bla','','3_9781484217290_2_1.jpg',1,1),(3,3,'PHP Arrays','','','','3_9781484225554.jpg',1,2),(4,1,'Expert Oracle Database Architecture','','','','1_9781430229469.jpg',1,3),(5,1,'Expert Oracle Database Architecture','','','','1_9781430262985.jpg',1,3),(6,2,'Expert Oracle Indexing and Access Paths',NULL,NULL,NULL,'2_9781484219836.jpg',NULL,NULL),(7,2,'Oracle RMAN Database Duplication',NULL,NULL,NULL,'2_9781484211137.jpg',NULL,NULL),(8,2,'RMAN Recipes for Oracle Database',NULL,NULL,NULL,'2_9781430248361.jpg',NULL,NULL),(9,2,'Linux and Solaris Recipes for Oracle DBAs','Linux and Solaris Recipes for Oracle DBAs, 2nd Edition is an example–based book on managing Oracle Database under Linux and Solaris.','Linux and Solaris Recipes for Oracle DBAs, 2nd Edition is an example–based book on managing Oracle Database under Linux and Solaris.','','2_linux_and_solaris_recipes_3.jpg',NULL,NULL),(10,2,'Linux and Solaris Recipes for Oracle DBAs','Linux and Solaris Recipes for Oracle DBAs, 2nd Edition is an example–based book on managing Oracle Database under Linux and Solaris.','Linux and Solaris Recipes for Oracle DBAs, 2nd Edition is an example–based book on managing Oracle Database under Linux and Solaris.','','2_linux_and_solaris_recipes_2.jpg',NULL,NULL),(11,4,'Ruby on Rails Tutorial: Learn Web Development with Rails','Ruby on Rails Tutorial: Learn Web Development with Rails (4th Edition) (Addison-Wesley Professional Ruby Series) 4th Edition','Ruby on Rails Tutorial: Learn Web Development with Rails (4th Edition) (Addison-Wesley Professional Ruby Series) 4th Edition','','MH-ruby-on-rails_1.jpg',NULL,NULL),(12,4,'Ruby on Rails Tutorial','Ruby on Rails Tutorial','Ruby on Rails Tutorial','','MH-ruby-on-rails_2.jpg',NULL,NULL),(13,1,'Expert One-on-One Oracle','Expert One-on-One Oracle','A proven best-seller by the most recognized Oracle expert in the world','','TK-expert_one-to-one_1.jpg',NULL,NULL),(14,1,'Expert One-on-One Oracle4','Expert One-on-One Oracle4','','','TK-expert_one-to-one_3.jpg',NULL,NULL),(15,2,'Pro Oracle Database 12c Administration ','Pro Oracle Database 12c Administration 2nd (second) Edition by Kuhn, Darl published by Apress (2013)','Pro Oracle Database 12c Administration 2nd (second) Edition by Kuhn, Darl published by Apress (2013)','','pro_oracle_12c_administration.jpg',NULL,NULL),(16,2,'Oracle Database Transactions and Locking Revealed','Oracle Database Transactions and Locking Revealed 1st ed. Edition','Oracle Database Transactions and Locking Revealed 1st ed. Edition','','oracle_database_transactions_2.jpg',NULL,NULL),(17,2,'Oracle Database Transactions and Locking Revealed 2','Oracle Database Transactions and Locking Revealed 2','Oracle Database Transactions and Locking Revealed 2','','oracle_database_transactions_4.jpg',NULL,NULL),(18,3,'PHP Arrays','PHP Arrays','PHP Arrays','','3_9781484225554_1.jpg',NULL,NULL),(19,3,'PHP Arrays','PHP Arrays','','','3_9781484225554_3_1.jpg',NULL,NULL),(20,5,'PHP & MySQL: Novice to Ninja','Get Up to Speed With PHP the Easy Way','PHP & MySQL: Novice to Ninja, 6th Edition is a hands-on guide to learning all the tools, principles, and techniques needed to build a fully functional application using PHP & MySQL. Comprehensively updated to cover PHP 7 and modern best practice, this practical and fun book covers everything from installing PHP and MySQL through to creating a complete online content management system.','','or_1_1.jpg',2,2),(21,6,'Build Your Own Database Driven Website Using PHP and MySQL','Learning PHP & MySQL Has Never Been So Easy!','Build Your Own Database-Driven Website Using PHP & MySQL is a practical guide for first-time users of PHP & MySQL that teaches readers by creating a fully working Content Management System, Shopping Cart and other real-world applications. There has been a marked increase in the adoption of PHP, most notably in the beginning to intermediate levels. PHP now boasts over 30% of the server side scripting market (Source: php.weblogs.com).','','or_2_1.jpg',2,2),(22,5,'PHP & MySQL: Novice to Ninja2','','','','or_1111_2.jpg',2,2),(23,8,'Practical Ruby for System Administration','The only book to describe Ruby for system administration and the only one that covers general system administration topics','Ruby has set the world on fire, proving itself a serious challenger to Perl and Python in all spheres. In particular, more and more people are discovering that Ruby\'s flexibility, superb feature set, and gentle learning curve make it a natural choice for system administration tasks, from the humblest server to the largest enterprise deployment.','','practical_ruby_for_sys_admin.png',1,1);
/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Ruby on Rails3'),(2,'PHP'),(3,'Oracle'),(4,'Perl'),(5,'Linux'),(6,'Open Source');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publishers`
--

DROP TABLE IF EXISTS `publishers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publishers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `about` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publishers`
--

LOCK TABLES `publishers` WRITE;
/*!40000 ALTER TABLE `publishers` DISABLE KEYS */;
INSERT INTO `publishers` VALUES (1,'Apress','Apress is a press company ...'),(2,'O\'Reilly','O\'Reilly press company ...'),(3,'Oracle Press','Oracle Press is company');
/*!40000 ALTER TABLE `publishers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(40) NOT NULL,
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `adress` varchar(100) DEFAULT NULL,
  `post_number` int(10) DEFAULT NULL,
  `phone_number` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'bborkovic','pass',NULL,NULL,NULL,NULL,NULL,NULL,NULL);
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

-- Dump completed on 2017-02-13 22:15:03
