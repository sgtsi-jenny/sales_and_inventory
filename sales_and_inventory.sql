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

/*Table structure for table `bad_orders` */

DROP TABLE IF EXISTS `bad_orders`;

CREATE TABLE `bad_orders` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `returned_id` varchar(255) DEFAULT NULL,
  `sales_id` varchar(255) DEFAULT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `quantity` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `bad_orders` */

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `category_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `categories` */

/*Table structure for table `company` */

DROP TABLE IF EXISTS `company`;

CREATE TABLE `company` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `company` */

/*Table structure for table `customer_address` */

DROP TABLE IF EXISTS `customer_address`;

CREATE TABLE `customer_address` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `customer_id` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `customer_address` */

/*Table structure for table `customers` */

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `customer_code` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `telephone_number` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(255) DEFAULT NULL,
  `birth_date` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `customers` */

/*Table structure for table `invoice_master` */

DROP TABLE IF EXISTS `invoice_master`;

CREATE TABLE `invoice_master` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `sales_id` varchar(255) DEFAULT NULL,
  `bill_to` varchar(255) DEFAULT NULL,
  `ship_to` varchar(255) DEFAULT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `terms` varchar(255) DEFAULT NULL,
  `payment_due` varchar(255) DEFAULT NULL,
  `date_issued` varchar(255) DEFAULT NULL,
  `total_units` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `invoice_master` */

/*Table structure for table `payment` */

DROP TABLE IF EXISTS `payment`;

CREATE TABLE `payment` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `pay_date` varchar(255) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `sales_id` varchar(255) DEFAULT NULL,
  `invoice_id` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `payment` */

/*Table structure for table `po_details` */

DROP TABLE IF EXISTS `po_details`;

CREATE TABLE `po_details` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `po_id` varchar(255) DEFAULT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `qty_received` varchar(255) DEFAULT NULL,
  `qty_ordered` varchar(255) DEFAULT NULL,
  `unit_cost` varchar(255) DEFAULT NULL,
  `total_cost` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `po_details` */

/*Table structure for table `po_master` */

DROP TABLE IF EXISTS `po_master`;

CREATE TABLE `po_master` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `po_number` varchar(255) DEFAULT NULL,
  `purchased_date` varchar(255) DEFAULT NULL,
  `delivery_date` varchar(255) DEFAULT NULL,
  `supplier_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `po_master` */

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `product_code` varchar(255) DEFAULT NULL,
  `category_id` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `selling_price` varchar(255) DEFAULT NULL,
  `wholesale_price` varchar(255) DEFAULT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `current_quantity` varchar(255) DEFAULT NULL,
  `minimum_quantity` varchar(255) DEFAULT NULL,
  `maximum_quantity` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `products` */

/*Table structure for table `returned_items` */

DROP TABLE IF EXISTS `returned_items`;

CREATE TABLE `returned_items` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `sales_id` varchar(255) DEFAULT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `quantity` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `returned_items` */

/*Table structure for table `sales_details` */

DROP TABLE IF EXISTS `sales_details`;

CREATE TABLE `sales_details` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `product_id` varchar(255) DEFAULT NULL,
  `sales_id` varchar(255) DEFAULT NULL,
  `quantity` varchar(255) DEFAULT NULL,
  `total_cost` varchar(255) DEFAULT NULL,
  `discount` varchar(255) DEFAULT NULL,
  `tax` varchar(255) DEFAULT NULL,
  `sales_status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sales_details` */

/*Table structure for table `sales_master` */

DROP TABLE IF EXISTS `sales_master`;

CREATE TABLE `sales_master` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `sales_code` varchar(255) DEFAULT NULL,
  `date_issue` varchar(255) DEFAULT NULL,
  `shipment_date` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `tax_type` varchar(255) DEFAULT NULL,
  `total_amount` varchar(255) DEFAULT NULL,
  `price_list` varchar(255) DEFAULT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sales_master` */

/*Table structure for table `shipments` */

DROP TABLE IF EXISTS `shipments`;

CREATE TABLE `shipments` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `bill_to` varchar(255) DEFAULT NULL,
  `ship_to` varchar(255) DEFAULT NULL,
  `ship_from` varchar(255) DEFAULT NULL,
  `date_packed` varchar(255) DEFAULT NULL,
  `delivery_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `shipments` */

/*Table structure for table `status` */

DROP TABLE IF EXISTS `status`;

CREATE TABLE `status` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `status` */

/*Table structure for table `stock_adjustments` */

DROP TABLE IF EXISTS `stock_adjustments`;

CREATE TABLE `stock_adjustments` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `sa_code` varchar(255) DEFAULT NULL,
  `item_id` varchar(255) DEFAULT NULL,
  `quantity_change` varchar(255) DEFAULT NULL,
  `date_adjusted` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `stock_adjustments` */

/*Table structure for table `stock_status` */

DROP TABLE IF EXISTS `stock_status`;

CREATE TABLE `stock_status` (
  `id` int(255) NOT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `status_id` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `stock_status` */

/*Table structure for table `supplier_products` */

DROP TABLE IF EXISTS `supplier_products`;

CREATE TABLE `supplier_products` (
  `id` int(255) NOT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `supplier_id` varchar(255) DEFAULT NULL,
  `unit_cost` varchar(255) DEFAULT NULL,
  `is_main` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `supplier_products` */

/*Table structure for table `suppliers` */

DROP TABLE IF EXISTS `suppliers`;

CREATE TABLE `suppliers` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `suppliers` */

/*Table structure for table `tax` */

DROP TABLE IF EXISTS `tax`;

CREATE TABLE `tax` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `pecentage` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tax` */

/*Table structure for table `user_types` */

DROP TABLE IF EXISTS `user_types`;

CREATE TABLE `user_types` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_types` */

insert  into `user_types`(`id`,`name`,`is_deleted`) values (1,'Administrator',0),(2,'Inventory',0);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
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

insert  into `users`(`id`,`user_type_id`,`first_name`,`middle_name`,`last_name`,`username`,`password`,`email`,`contact_no`,`gender`,`last_login`,`is_deleted`,`is_login`,`last_activity`,`is_active`,`security_question`,`security_answer`) values (1,1,'Jenny','Bueno','Bercasio','admin','TNTz0EXkSq4dC+kr8w8+UF14gOTFdx6RSEJpaGwQ7v4=','a@a.com','0940124','Female','0000-00-00',0,0,'2016-05-11 16:39:45',1,'True love?','mom');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
