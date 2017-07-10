/*
SQLyog - Free MySQL GUI v5.14
Host - 5.5.16 : Database - mint_intered
*********************************************************************
Server version : 5.5.16
*/

SET NAMES utf8;

SET SQL_MODE='';
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

/*Table structure for table `tbl_course` */

DROP TABLE IF EXISTS `tbl_course`;

CREATE TABLE `tbl_course` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `course_code` varchar(100) DEFAULT NULL,
  `course_name` varchar(350) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `publish` enum('Y','N') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `course_code` (`course_code`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_course` */

insert into `tbl_course` (`id`,`course_code`,`course_name`,`description`,`publish`) values (1,'T','Computer Science',NULL,'Y');
insert into `tbl_course` (`id`,`course_code`,`course_name`,`description`,`publish`) values (2,'T','Information Technology, Specialization in Game Design & Development',NULL,'Y');
insert into `tbl_course` (`id`,`course_code`,`course_name`,`description`,`publish`) values (3,'T','Information Technology, Specialization in Apple Technology',NULL,'Y');
insert into `tbl_course` (`id`,`course_code`,`course_name`,`description`,`publish`) values (4,'V','New Media Arts, Specialization in Digital Arts & Design',NULL,'Y');
insert into `tbl_course` (`id`,`course_code`,`course_name`,`description`,`publish`) values (5,'V','New Media Arts, Specialization in Animation',NULL,'Y');
insert into `tbl_course` (`id`,`course_code`,`course_name`,`description`,`publish`) values (6,'V','New Media Arts, Specialization in Interior Design',NULL,'Y');
insert into `tbl_course` (`id`,`course_code`,`course_name`,`description`,`publish`) values (7,'V','Film and Communication',NULL,'Y');
insert into `tbl_course` (`id`,`course_code`,`course_name`,`description`,`publish`) values (8,'B','Entreprenuerial Management',NULL,'Y');
insert into `tbl_course` (`id`,`course_code`,`course_name`,`description`,`publish`) values (9,'B','International Marketing, Advertising and Communications',NULL,'Y');
insert into `tbl_course` (`id`,`course_code`,`course_name`,`description`,`publish`) values (10,'B','International Marketing, Advertising and Communications, Specialization in Fashion Merchandising and Design',NULL,'Y');
insert into `tbl_course` (`id`,`course_code`,`course_name`,`description`,`publish`) values (11,'B','Environmental Management',NULL,'Y');
insert into `tbl_course` (`id`,`course_code`,`course_name`,`description`,`publish`) values (12,'P','Music Business Management',NULL,'Y');
insert into `tbl_course` (`id`,`course_code`,`course_name`,`description`,`publish`) values (13,'P','Music Business Management, Specialization in Performance',NULL,'Y');
insert into `tbl_course` (`id`,`course_code`,`course_name`,`description`,`publish`) values (14,'P','Music Business Management, Specialization in Sound Production',NULL,'Y');
insert into `tbl_course` (`id`,`course_code`,`course_name`,`description`,`publish`) values (15,'P','Theater Arts',NULL,'Y');

/*Table structure for table `tbl_student` */

DROP TABLE IF EXISTS `tbl_student`;

CREATE TABLE `tbl_student` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `highschool` varchar(500) DEFAULT NULL,
  `year` varchar(100) DEFAULT NULL,
  `course` bigint(20) DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_student` */

SET SQL_MODE=@OLD_SQL_MODE;