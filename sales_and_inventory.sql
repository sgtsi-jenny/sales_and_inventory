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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `adjustment_status` */

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `categories` */

insert  into `categories`(`category_id`,`name`,`is_deleted`) values (1,'Phone',0),(2,'Dress',0),(3,'Jewelry',0),(4,'Sports Shoes',0),(5,'Watch',0),(6,'T-shirt',0);

/*Table structure for table `customer_address` */

DROP TABLE IF EXISTS `customer_address`;

CREATE TABLE `customer_address` (
  `customer_add_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) DEFAULT NULL,
  `label_address` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`customer_add_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `customer_address` */

insert  into `customer_address`(`customer_add_id`,`customer_id`,`label_address`,`address`) values (1,1,'Home','Batasan Hills Quezon City'),(2,1,'Work','Tektite Pasig City'),(3,2,'Home angono','Rizal'),(4,2,'Work ANgono','Pasig City Phil.');

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
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `customers` */

insert  into `customers`(`customer_id`,`customer_name`,`tin`,`description`,`fax`,`telephone_number`,`mobile_number`,`birth_date`,`website`,`email`,`is_deleted`) values (1,'BHF','2345678','bhf bank of the phil.','45678','45678','54678','19930520','www.bhf.com','bhf@gmail.com',0),(2,'Angono','4567868','angono memorial','567','5467','567','19900510','www.angono.com','angono@ymail.com',0);

/*Table structure for table `foo` */

DROP TABLE IF EXISTS `foo`;

CREATE TABLE `foo` (
  `the_key` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`the_key`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `foo` */

insert  into `foo`(`the_key`,`name`) values (0000000001,NULL),(0000000002,NULL),(0000000003,NULL),(0000000004,NULL),(0000000005,NULL),(0000000006,'dfds'),(0000000007,'dsg');

/*Table structure for table `invoice_master` */

DROP TABLE IF EXISTS `invoice_master`;

CREATE TABLE `invoice_master` (
  `invoice_master_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sales_id` bigint(255) DEFAULT NULL,
  `bill_to` varchar(255) DEFAULT NULL,
  `ship_to` varchar(255) DEFAULT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `terms` varchar(255) DEFAULT NULL,
  `payment_due` varchar(8) DEFAULT NULL,
  `date_issued` varchar(8) DEFAULT NULL,
  `total_units` varchar(9) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`invoice_master_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `invoice_master` */

/*Table structure for table `measurements` */

DROP TABLE IF EXISTS `measurements`;

CREATE TABLE `measurements` (
  `measurement_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `abv` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`measurement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `measurements` */

/*Table structure for table `payment_status` */

DROP TABLE IF EXISTS `payment_status`;

CREATE TABLE `payment_status` (
  `payment_status_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`payment_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `payment_status` */

insert  into `payment_status`(`payment_status_id`,`name`) values (1,'unpaid'),(2,'paid');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `po_details` */

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
  PRIMARY KEY (`po_master_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `po_master` */

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `po_payments` */

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `po_received` */

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
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `products` */

insert  into `products`(`product_id`,`product_code`,`category_id`,`product_name`,`selling_price`,`wholesale_price`,`barcode`,`current_quantity`,`minimum_quantity`,`maximum_quantity`,`is_deleted`,`measurement_id`,`stock_status_id`,`description`) values (1,'prod0001',1,'iphone 5s','10000','15000','00000010','50','10','30',0,NULL,NULL,NULL),(2,'prod0002',1,'iphone 6plus','30000','25000','00000020','10','10','50',0,NULL,NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `sales_details` */

insert  into `sales_details`(`sales_detail_id`,`product_id`,`sales_master_id`,`quantity`,`unit_cost`,`total_cost`,`discount`,`tax`,`is_deleted`) values (1,1,1,'5','10000','50000',NULL,NULL,0),(2,2,1,'5','50000','250000',NULL,NULL,0),(3,2,2,'1','10000','10000',NULL,NULL,0);

/*Table structure for table `sales_master` */

DROP TABLE IF EXISTS `sales_master`;

CREATE TABLE `sales_master` (
  `sales_master_id` bigint(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `date_issue` varchar(255) DEFAULT NULL,
  `shipment_id` bigint(20) DEFAULT NULL,
  `tax_id` bigint(20) DEFAULT NULL,
  `total_amount` varchar(255) DEFAULT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `sales_status_id` bigint(20) DEFAULT NULL,
  `payment_status_id` bigint(20) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`sales_master_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `sales_master` */

insert  into `sales_master`(`sales_master_id`,`date_issue`,`shipment_id`,`tax_id`,`total_amount`,`customer_id`,`user_id`,`sales_status_id`,`payment_status_id`,`is_deleted`) values (0000000001,'20160518',1,NULL,'300000',1,1,2,1,0),(0000000002,'2016-519',1,NULL,'10000',1,1,2,1,0),(0000000003,'32',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0);

/*Table structure for table `sales_payments` */

DROP TABLE IF EXISTS `sales_payments`;

CREATE TABLE `sales_payments` (
  `sales_payment_id` int(255) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `amount` varchar(12) DEFAULT NULL,
  `pay_date` varchar(8) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `sales_master_id` bigint(20) DEFAULT NULL,
  `invoice_master_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`sales_payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `sales_payments` */

insert  into `sales_payments`(`sales_payment_id`,`type`,`amount`,`pay_date`,`reference`,`sales_master_id`,`invoice_master_id`,`user_id`,`is_deleted`) values (1,'sf','50000','20160518','sdf',1,1,1,0),(2,'sf','10000','20160518','fghf',1,1,1,0),(3,'sf','1000','20160519','tfgds',2,1,1,0);

/*Table structure for table `sales_status` */

DROP TABLE IF EXISTS `sales_status`;

CREATE TABLE `sales_status` (
  `sales_status_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sales_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `sales_status` */

insert  into `sales_status`(`sales_status_id`,`name`) values (1,'quote'),(2,'allocated'),(3,'invoiced'),(4,'shipped');

/*Table structure for table `shipments` */

DROP TABLE IF EXISTS `shipments`;

CREATE TABLE `shipments` (
  `shipment_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `bill_to` varchar(255) DEFAULT NULL,
  `ship_to` varchar(255) DEFAULT NULL,
  `ship_from` varchar(255) DEFAULT NULL,
  `date_delivered` varchar(8) DEFAULT NULL,
  `delivery_type` varchar(255) DEFAULT NULL,
  `date_shipped` varchar(8) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`shipment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `shipments` */

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
  PRIMARY KEY (`stock_adj_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `stock_adjustments` */

/*Table structure for table `stock_status` */

DROP TABLE IF EXISTS `stock_status`;

CREATE TABLE `stock_status` (
  `stock_status_id` bigint(20) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`stock_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `stock_status` */

/*Table structure for table `supplier_products` */

DROP TABLE IF EXISTS `supplier_products`;

CREATE TABLE `supplier_products` (
  `supplier_product_id` bigint(20) NOT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `supplier_id` bigint(20) DEFAULT NULL,
  `unit_cost` varchar(12) DEFAULT NULL,
  `is_main` tinyint(1) DEFAULT '0',
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`supplier_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `supplier_products` */

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `suppliers` */

insert  into `suppliers`(`supplier_id`,`name`,`description`,`contact_number`,`address`,`email`,`is_deleted`) values (1,'BDO','Banco de ORo desc','028496','Ortigas Center','dbo@info.ph',0),(2,'Euro Shop','this a test update','089678','Makati City ','euro@gmail.com',0),(3,'belo','belo med group','0979869','Makati City ','belo@gmail.com',0);

/*Table structure for table `tax` */

DROP TABLE IF EXISTS `tax`;

CREATE TABLE `tax` (
  `tax_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `percentage` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tax` */

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
  `user_id` bigint(20) NOT NULL,
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
  `last_activity` datetime DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1',
  `security_question` varchar(255) DEFAULT NULL,
  `security_answer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table for users';

/*Data for the table `users` */

insert  into `users`(`user_id`,`user_type_id`,`first_name`,`middle_name`,`last_name`,`username`,`password`,`email`,`contact_no`,`gender`,`last_login`,`is_deleted`,`is_login`,`last_activity`,`is_active`,`security_question`,`security_answer`) values (1,1,'Jenny','Bueno','Bercasio','admin','TNTz0EXkSq4dC+kr8w8+UF14gOTFdx6RSEJpaGwQ7v4=','a@a.com','0940124','Female','0000-00-00',0,0,'2016-05-19 17:29:05',1,'True love?','mom');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
