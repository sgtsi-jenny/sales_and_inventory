/*
SQLyog Ultimate v8.55 
MySQL - 5.5.5-10.1.9-MariaDB : Database - sales_and_inventory
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`sales_and_inventory` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `sales_and_inventory`;

/*Table structure for table `adjustment_status` */

DROP TABLE IF EXISTS `adjustment_status`;

CREATE TABLE `adjustment_status` (
  `adj_status_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varbinary(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`adj_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `adjustment_status` */

insert  into `adjustment_status`(`adj_status_id`,`name`,`is_deleted`) values (1,'New Product',0),(2,'Returned',0),(3,'Production of Goods',0),(4,'Damaged',0),(5,'Shrinkage',0),(6,'Promotion',0);

/*Table structure for table `audit_trails` */

DROP TABLE IF EXISTS `audit_trails`;

CREATE TABLE `audit_trails` (
  `audit_trail_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `action` varchar(255) DEFAULT NULL,
  `action_desc` varchar(255) DEFAULT NULL,
  `action_date` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`audit_trail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `audit_trails` */

/*Table structure for table `bad_orders` */

DROP TABLE IF EXISTS `bad_orders`;

CREATE TABLE `bad_orders` (
  `bad_order_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sales_master_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `quantity` varchar(9) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`bad_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `bad_orders` */

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `category_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `categories` */

insert  into `categories`(`category_id`,`name`,`is_deleted`) values (1,'Phone',0),(2,'Dress',0),(3,'Jewelry',0),(4,'Sports Shoes',0),(5,'Watch 2',0),(6,'T-shirt',0),(7,'Gadgets',0),(8,'Service',0);

/*Table structure for table `customer_address` */

DROP TABLE IF EXISTS `customer_address`;

CREATE TABLE `customer_address` (
  `customer_add_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) DEFAULT NULL,
  `label_address` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`customer_add_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `customer_address` */

insert  into `customer_address`(`customer_add_id`,`customer_id`,`label_address`,`address`,`is_deleted`) values (1,1,'Alabang','Las Pinas Pilipinas',0),(2,1,'QC','Project 4 Quezon City',0),(3,1,'wqw','ewr',0),(4,2,'Rizal','Angono Rizal',0),(5,3,'Work','Pasig City',0);

/*Table structure for table `customer_contacts` */

DROP TABLE IF EXISTS `customer_contacts`;

CREATE TABLE `customer_contacts` (
  `contact_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `fax_number` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `customer_contacts` */

/*Table structure for table `customers` */

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `customer_id` int(255) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) DEFAULT NULL,
  `tin` varchar(15) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `fax` varchar(35) DEFAULT NULL,
  `telephone_number` varchar(35) DEFAULT NULL,
  `mobile_number` varchar(35) DEFAULT NULL,
  `birth_date` varchar(8) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `is_top_company` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `customers` */

insert  into `customers`(`customer_id`,`customer_name`,`tin`,`description`,`fax`,`telephone_number`,`mobile_number`,`birth_date`,`website`,`email`,`is_deleted`,`is_top_company`) values (1,'Euro Shop','100000000000002','Cars Shop','2776009','19729837','7568585','19901111','euroshop.com','euro@gmail.com',0,1),(2,'Angono Memorial Park','3456789','sample customer','56768798','54678','5678','19901209','angono.com','angelo@gmail.com',0,0),(3,'Trixia Ganda','09234567890','Dyesebel sa gabi','0965444','3550987','09772345678','19940528','trix.com','trix_ganda@gmail.com',0,0);

/*Table structure for table `fastslow` */

DROP TABLE IF EXISTS `fastslow`;

CREATE TABLE `fastslow` (
  `fs_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` varchar(50) DEFAULT NULL,
  `first_month` varchar(50) DEFAULT NULL,
  `second_month` varchar(50) DEFAULT NULL,
  `current_month` varchar(50) DEFAULT NULL,
  `average_sales` varchar(50) DEFAULT NULL,
  `fs_date` varchar(50) DEFAULT NULL,
  `fs_status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`fs_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `fastslow` */

insert  into `fastslow`(`fs_id`,`product_id`,`first_month`,`second_month`,`current_month`,`average_sales`,`fs_date`,`fs_status`) values (1,'1','0','0','5','1.67','20160531','Slow Moving'),(2,'2','2','1','5','2.67','20160531','Slow Moving');

/*Table structure for table `invoice_master` */

DROP TABLE IF EXISTS `invoice_master`;

CREATE TABLE `invoice_master` (
  `invoice_master_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sales_master_id` bigint(255) DEFAULT NULL,
  `bill_to` varchar(255) DEFAULT NULL,
  `ship_to` varchar(255) DEFAULT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `terms` varchar(255) DEFAULT NULL,
  `payment_due` varchar(8) DEFAULT NULL,
  `date_issued` varchar(8) DEFAULT NULL,
  `total_units` varchar(9) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`invoice_master_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `invoice_master` */

insert  into `invoice_master`(`invoice_master_id`,`sales_master_id`,`bill_to`,`ship_to`,`customer_id`,`terms`,`payment_due`,`date_issued`,`total_units`,`is_deleted`,`description`) values (1,1,'4','4',2,'10','20160617','20160607','1',0,'just just'),(2,3,'5','5',3,'60','20160807','20160608','15',0,'why so '),(3,2,'4','4',2,'10','20160620','20160607','5',0,'saa'),(4,5,'1','2',1,'10','20160624','20160614','7',0,'aqr'),(5,9,'5','5',3,'10','20160624','20160614','4',0,'erfghj'),(6,10,'5','5',3,'5','20160619','20160614','17',0,'dfcgvhbj'),(7,11,'1','2',1,'5','20160619','20160614','25',0,'wedf');

/*Table structure for table `measurements` */

DROP TABLE IF EXISTS `measurements`;

CREATE TABLE `measurements` (
  `measurement_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `abv` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`measurement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `measurements` */

insert  into `measurements`(`measurement_id`,`abv`,`name`,`is_deleted`) values (1,'length','haba',0),(2,'pcs','pieces',0),(3,'kgs','Kilograms',0);

/*Table structure for table `payment_status` */

DROP TABLE IF EXISTS `payment_status`;

CREATE TABLE `payment_status` (
  `payment_status_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`payment_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `payment_status` */

insert  into `payment_status`(`payment_status_id`,`name`) values (1,'Unpaid'),(2,'Paid');

/*Table structure for table `po_details` */

DROP TABLE IF EXISTS `po_details`;

CREATE TABLE `po_details` (
  `po_detail_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `po_master_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `qty_ordered` varchar(9) DEFAULT NULL,
  `unit_cost` varchar(12) DEFAULT NULL,
  `total_cost` varchar(12) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`po_detail_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `po_details` */

insert  into `po_details`(`po_detail_id`,`po_master_id`,`product_id`,`qty_ordered`,`unit_cost`,`total_cost`,`is_deleted`) values (1,0,2,'2','25000','50000',0),(2,0,1,'10','8000','80000',0),(3,0,1,'4','8000','32000',0),(4,0,2,'6','25000','150000',0),(5,1,1,'5','8000','40000',0),(6,1,2,'3','25000','75000',0),(7,2,3,'9','7500','67500',0),(8,3,1,'5','8000','40000',0),(9,3,2,'9','25000','225000',0);

/*Table structure for table `po_master` */

DROP TABLE IF EXISTS `po_master`;

CREATE TABLE `po_master` (
  `po_master_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `po_number` varchar(100) DEFAULT NULL,
  `purchased_date` varchar(8) DEFAULT NULL,
  `supplier_id` bigint(20) DEFAULT NULL,
  `po_status_id` tinyint(1) DEFAULT NULL,
  `payment_status_id` tinyint(1) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `date_modified` bigint(8) DEFAULT NULL,
  `total_amount` varchar(12) DEFAULT NULL,
  `ship_to` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`po_master_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `po_master` */

insert  into `po_master`(`po_master_id`,`po_number`,`purchased_date`,`supplier_id`,`po_status_id`,`payment_status_id`,`is_deleted`,`date_modified`,`total_amount`,`ship_to`) values (1,NULL,'20160608',1,2,2,0,20160608,'115000','eshfdj'),(2,NULL,'20160608',2,1,1,0,20160608,'67500','l'),(3,NULL,'20160608',2,3,2,0,20160608,'265000','pasig');

/*Table structure for table `po_payments` */

DROP TABLE IF EXISTS `po_payments`;

CREATE TABLE `po_payments` (
  `po_payment_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `po_master_id` bigint(20) DEFAULT NULL,
  `amount` varchar(8) DEFAULT NULL,
  `date_paid` varchar(12) DEFAULT NULL,
  `is_void` tinyint(1) DEFAULT '0',
  `remarks` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`po_payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `po_payments` */

insert  into `po_payments`(`po_payment_id`,`po_master_id`,`amount`,`date_paid`,`is_void`,`remarks`,`is_deleted`) values (1,1,'115000','20160608',0,'qwaesrdfgh',0),(2,3,'165000','20160608',0,'partial',0),(3,3,'100000','20160608',0,'full',0);

/*Table structure for table `po_received` */

DROP TABLE IF EXISTS `po_received`;

CREATE TABLE `po_received` (
  `po_received_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `po_master_id` bigint(20) DEFAULT NULL,
  `date_received` varchar(8) DEFAULT NULL,
  `qty_received` varchar(12) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`po_received_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `po_received` */

insert  into `po_received`(`po_received_id`,`po_master_id`,`date_received`,`qty_received`,`product_id`,`reference_number`,`remarks`,`is_deleted`) values (1,1,'20160608','5',1,'3245670','dhfdudf',0),(2,3,'20160608','5',1,'32456787','ok',0),(3,3,'20160608','4',2,'345678','ok',0),(4,3,'20160608','5',2,'23456','kulang',0);

/*Table structure for table `po_status` */

DROP TABLE IF EXISTS `po_status`;

CREATE TABLE `po_status` (
  `po_status_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`po_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `po_status` */

insert  into `po_status`(`po_status_id`,`name`) values (1,'active'),(2,'partially received'),(3,'received');

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `product_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_code` varchar(255) DEFAULT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `selling_price` varchar(12) DEFAULT NULL,
  `wholesale_price` varchar(12) DEFAULT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `current_quantity` varchar(9) DEFAULT NULL,
  `minimum_quantity` varchar(9) DEFAULT NULL,
  `maximum_quantity` varchar(9) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `measurement_id` bigint(20) DEFAULT NULL,
  `stock_status_id` bigint(20) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `product_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `products` */

insert  into `products`(`product_id`,`product_code`,`category_id`,`product_name`,`selling_price`,`wholesale_price`,`barcode`,`current_quantity`,`minimum_quantity`,`maximum_quantity`,`is_deleted`,`measurement_id`,`stock_status_id`,`description`,`product_type`) values (1,'prod0001',1,'iphone 5s','10000','8000','00000010','120','10','30',0,2,1,'iphone 5s 16gb',NULL),(2,'prod0002',1,'iphone 6plus','30000','25000','00000020','209','10','50',0,2,1,'iphone 6plus 32 gb',NULL),(3,'prod0003',1,'samsung','15000','20000','00000030','300','10','20',0,2,1,'samsung s6',NULL),(4,'prod005',2,'Powerbank 1','1001','800','0000001005','117','20','150',1,1,NULL,'MI Powerbank 1',NULL),(5,'prod006',8,'Repair','1000','800','00012099','100','10','100',0,NULL,NULL,NULL,NULL);

/*Table structure for table `sales_details` */

DROP TABLE IF EXISTS `sales_details`;

CREATE TABLE `sales_details` (
  `sales_detail_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) DEFAULT NULL,
  `sales_master_id` bigint(20) DEFAULT NULL,
  `quantity` varchar(9) DEFAULT NULL,
  `unit_cost` varchar(12) DEFAULT NULL,
  `total_cost` varchar(12) DEFAULT NULL,
  `discount` varchar(5) DEFAULT NULL,
  `tax` varchar(5) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`sales_detail_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

/*Data for the table `sales_details` */

insert  into `sales_details`(`sales_detail_id`,`product_id`,`sales_master_id`,`quantity`,`unit_cost`,`total_cost`,`discount`,`tax`,`is_deleted`) values (2,1,1,'1','10000','10000','',NULL,0),(10,1,2,'3','10000','29400','2',NULL,0),(11,3,2,'2','15000','29100','3',NULL,0),(12,4,3,'5','1001','3753.75','25',NULL,0),(13,1,3,'10','10000','85000','15',NULL,0),(14,0,4,'','',NULL,'',NULL,0),(16,1,6,'4','10000','40000','',NULL,0),(17,1,7,'4','10000','40000','',NULL,0),(18,2,7,'3','30000','90000','',NULL,0),(19,1,8,'4','10000','40000','',NULL,0),(20,2,8,'4','30000','120000','',NULL,0),(24,4,9,'4','1001','4004','',NULL,0),(25,1,5,'2','10000','20000','',NULL,0),(26,2,5,'5','30000','150000','',NULL,0),(27,3,10,'5','15000','75000','',NULL,0),(28,2,10,'12','30000','360000','',NULL,0),(29,1,11,'25','10000','250000','',NULL,0),(30,4,12,'12','1001','12012','',NULL,0);

/*Table structure for table `sales_master` */

DROP TABLE IF EXISTS `sales_master`;

CREATE TABLE `sales_master` (
  `sales_master_id` bigint(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `date_issue` varchar(8) DEFAULT NULL,
  `shipment_id` bigint(20) DEFAULT NULL,
  `tax_id` bigint(20) DEFAULT NULL,
  `total_amount` varchar(255) DEFAULT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `sales_status_id` bigint(20) DEFAULT NULL,
  `payment_status_id` bigint(20) DEFAULT NULL,
  `bill_to` bigint(20) DEFAULT NULL,
  `ship_to` bigint(20) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `description` varchar(255) DEFAULT NULL,
  `date_modified` varchar(8) DEFAULT NULL,
  `is_void` tinyint(1) DEFAULT '0',
  `terms` int(11) DEFAULT NULL,
  PRIMARY KEY (`sales_master_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `sales_master` */

insert  into `sales_master`(`sales_master_id`,`date_issue`,`shipment_id`,`tax_id`,`total_amount`,`customer_id`,`user_id`,`sales_status_id`,`payment_status_id`,`bill_to`,`ship_to`,`is_deleted`,`description`,`date_modified`,`is_void`,`terms`) values (0000000001,'20160607',1,NULL,'37500',2,1,4,2,NULL,NULL,0,'test angono','20160607',0,NULL),(0000000002,'20160607',3,NULL,'24350',2,1,4,1,NULL,NULL,0,'','20160614',0,NULL),(0000000003,'20160608',1,NULL,'88753.75',3,1,3,2,NULL,NULL,0,'ang gondo aaaaayyyyyyyyyyyyyy!','20160609',1,NULL),(0000000005,'20160609',2,NULL,'40000',1,1,4,1,NULL,NULL,0,'','20160614',0,NULL),(0000000009,'20160609',4,NULL,'4004',3,1,4,1,NULL,NULL,0,'','20160614',0,NULL),(0000000010,'20160614',5,NULL,'435000',3,1,4,1,NULL,NULL,0,'qewq','20160614',0,NULL),(0000000011,'20160614',6,NULL,'250000',1,1,4,1,NULL,NULL,0,'fu','20160614',0,NULL),(0000000012,'20160614',NULL,NULL,'12012',2,1,2,1,NULL,NULL,0,'sfs','20160616',0,NULL);

/*Table structure for table `sales_payment_type` */

DROP TABLE IF EXISTS `sales_payment_type`;

CREATE TABLE `sales_payment_type` (
  `payment_type_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`payment_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `sales_payment_type` */

insert  into `sales_payment_type`(`payment_type_id`,`name`) values (1,'Cash'),(2,'Credit Card');

/*Table structure for table `sales_payments` */

DROP TABLE IF EXISTS `sales_payments`;

CREATE TABLE `sales_payments` (
  `sales_payment_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` bigint(10) DEFAULT NULL,
  `amount` varchar(12) DEFAULT NULL,
  `pay_date` varchar(8) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `sales_master_id` bigint(20) DEFAULT NULL,
  `invoice_master_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `is_voided` tinyint(1) DEFAULT '0',
  `date_voided` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`sales_payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `sales_payments` */

insert  into `sales_payments`(`sales_payment_id`,`type`,`amount`,`pay_date`,`reference`,`sales_master_id`,`invoice_master_id`,`user_id`,`is_deleted`,`is_voided`,`date_voided`) values (1,1,'10000','20160607','LBC',1,1,1,0,0,NULL),(2,1,'70000','20160608','partial',3,2,1,0,1,'20160609'),(3,1,'18753.75','20160608','full',3,2,1,0,1,'20160609'),(4,1,'70000','20160608','full',3,2,1,0,1,'20160609'),(5,1,'8500','20160605','efghj',2,3,1,0,0,NULL);

/*Table structure for table `sales_status` */

DROP TABLE IF EXISTS `sales_status`;

CREATE TABLE `sales_status` (
  `sales_status_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sales_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `sales_status` */

insert  into `sales_status`(`sales_status_id`,`name`) values (1,'Quote'),(2,'Allocated'),(3,'Invoiced'),(4,'Shipped');

/*Table structure for table `shipments` */

DROP TABLE IF EXISTS `shipments`;

CREATE TABLE `shipments` (
  `shipment_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) DEFAULT NULL,
  `bill_to` bigint(20) DEFAULT NULL,
  `ship_to` bigint(20) DEFAULT NULL,
  `ship_from` varchar(255) DEFAULT NULL,
  `ship_service` varchar(255) DEFAULT NULL,
  `ship_method` varchar(255) DEFAULT NULL,
  `date_delivered` varchar(8) DEFAULT NULL,
  `date_shipped` varchar(8) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `sales_master_id` bigint(20) DEFAULT NULL,
  `tracking_code` varchar(255) DEFAULT NULL,
  `comments` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`shipment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `shipments` */

insert  into `shipments`(`shipment_id`,`customer_id`,`bill_to`,`ship_to`,`ship_from`,`ship_service`,`ship_method`,`date_delivered`,`date_shipped`,`is_deleted`,`sales_master_id`,`tracking_code`,`comments`) values (1,3,5,5,'sdfghjkl;','DHL','Air','20160509','20160508',0,3,'23456789','wfghj'),(2,1,1,2,'bahaghari','FX','Lakad','20160420','20160414',0,5,'234567890','ewrty'),(3,2,4,4,'ertfyuhij','wertfy','gertyui','20160614','20160614',0,2,'3456789','rtfyguhijo'),(4,3,5,5,'5678fgjhfjgk','dhl','air','20160525','20160514',0,9,'345678','rdfghj'),(5,3,5,5,'dfghjnkm','DHL','water','20160627','20160614',0,10,'34567890900','\r\nwedfghjk'),(6,1,3,2,'ergfhj','DHL','water','20160621','20160610',0,11,'34565','dfghj');

/*Table structure for table `stock_adj_details` */

DROP TABLE IF EXISTS `stock_adj_details`;

CREATE TABLE `stock_adj_details` (
  `stock_adjdetails_id` bigint(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `stock_adjmaster_id` bigint(10) DEFAULT NULL,
  `product_id` bigint(10) DEFAULT NULL,
  `quantity_received` varchar(12) DEFAULT NULL,
  `is_deleted` varchar(1) DEFAULT '0',
  PRIMARY KEY (`stock_adjdetails_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `stock_adj_details` */

insert  into `stock_adj_details`(`stock_adjdetails_id`,`stock_adjmaster_id`,`product_id`,`quantity_received`,`is_deleted`) values (0000000001,1,1,'3','0'),(0000000002,1,1,'2','0');

/*Table structure for table `stock_adj_master` */

DROP TABLE IF EXISTS `stock_adj_master`;

CREATE TABLE `stock_adj_master` (
  `stock_adjmaster_id` bigint(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `adj_status_id` bigint(10) DEFAULT NULL,
  `date_adjusted` varchar(8) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `total_cost` varchar(12) DEFAULT NULL,
  `is_reverted` varchar(1) DEFAULT NULL,
  `reverted_from` bigint(20) DEFAULT NULL,
  `total_quantity_received` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`stock_adjmaster_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `stock_adj_master` */

insert  into `stock_adj_master`(`stock_adjmaster_id`,`adj_status_id`,`date_adjusted`,`is_deleted`,`total_cost`,`is_reverted`,`reverted_from`,`total_quantity_received`) values (0000000001,3,'20160523',0,'00015000000','0',0,'3');

/*Table structure for table `stock_adjustments` */

DROP TABLE IF EXISTS `stock_adjustments`;

CREATE TABLE `stock_adjustments` (
  `stock_adj_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sales_master_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `quantity_change` varchar(9) DEFAULT NULL,
  `adj_status_id` bigint(20) DEFAULT NULL,
  `date_adjusted` varchar(8) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `notes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`stock_adj_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `stock_adjustments` */

/*Table structure for table `stock_status` */

DROP TABLE IF EXISTS `stock_status`;

CREATE TABLE `stock_status` (
  `stock_status_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `remarks` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`stock_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `stock_status` */

insert  into `stock_status`(`stock_status_id`,`remarks`,`is_deleted`) values (1,'Quote',0);

/*Table structure for table `supplier_products` */

DROP TABLE IF EXISTS `supplier_products`;

CREATE TABLE `supplier_products` (
  `supplier_product_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) DEFAULT NULL,
  `supplier_id` bigint(20) DEFAULT NULL,
  `unit_cost` varchar(12) DEFAULT NULL,
  `is_main` tinyint(1) DEFAULT '0',
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`supplier_product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `supplier_products` */

insert  into `supplier_products`(`supplier_product_id`,`product_id`,`supplier_id`,`unit_cost`,`is_main`,`is_deleted`) values (1,1,1,'8000',0,0),(2,3,3,'7500',0,0),(3,2,2,'25000',0,0),(4,4,1,'499',0,1);

/*Table structure for table `suppliers` */

DROP TABLE IF EXISTS `suppliers`;

CREATE TABLE `suppliers` (
  `supplier_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `suppliers` */

insert  into `suppliers`(`supplier_id`,`name`,`description`,`contact_number`,`address`,`email`,`is_deleted`) values (1,'BDO','Banco de ORo desc','028496','Ortigas Center','dbo@info.ph',0),(2,'Euro Shop','this a test update','089678','Makati City ','euro@gmail.com',0),(3,'belo','belo med group','0979869','Makati City ','belo@gmail.com',0),(4,'MI 22','MI supplier 2','08969868750','Makati City 2','mia@info.com',0);

/*Table structure for table `tax` */

DROP TABLE IF EXISTS `tax`;

CREATE TABLE `tax` (
  `tax_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tax_name` varchar(20) DEFAULT NULL,
  `percentage` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`tax_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `tax` */

insert  into `tax`(`tax_id`,`tax_name`,`percentage`) values (1,'VAT','12'),(2,'SERVICES','2'),(3,'GOODS','5');

/*Table structure for table `user_types` */

DROP TABLE IF EXISTS `user_types`;

CREATE TABLE `user_types` (
  `user_type_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_types` */

insert  into `user_types`(`user_type_id`,`name`,`is_deleted`) values (1,'Administrator',0),(2,'Inventory',0);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_type_id` bigint(20) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_no` varchar(255) NOT NULL,
  `gender` varchar(6) DEFAULT NULL,
  `last_login` date NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `is_login` tinyint(1) DEFAULT '0',
  `last_activity` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1',
  `security_question` varchar(255) DEFAULT NULL,
  `security_answer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Table for users';

/*Data for the table `users` */

insert  into `users`(`user_id`,`user_type_id`,`first_name`,`middle_name`,`last_name`,`username`,`password`,`email`,`contact_no`,`gender`,`last_login`,`is_deleted`,`is_login`,`last_activity`,`is_active`,`security_question`,`security_answer`) values (1,1,'Jenny','Bueno','Bercasio','admin','TNTz0EXkSq4dC+kr8w8+UF14gOTFdx6RSEJpaGwQ7v4=','a@a.com','0940124','Female','0000-00-00',0,1,'2016-06-17 11:36:45',1,'True love?','mom'),(2,2,'Eom','O','Molina','eom2','TNTz0EXkSq4dC+kr8w8+UF14gOTFdx6RSEJpaGwQ7v4=','eom@gmail.com','09876543210',NULL,'0000-00-00',0,0,'2016-06-14 13:37:32',1,'short hair','percy gf');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
