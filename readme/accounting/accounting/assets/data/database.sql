
CREATE TABLE `acc_account` (
  `account_id` int(11) NOT NULL AUTO_INCREMENT,
  `sector_name` varchar(255) NOT NULL,
  `sector_type` varchar(120) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `acc_bank` (
  `bank_id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(200) NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `opening_credit` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`bank_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `acc_company` (
  `company_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `mobile_no` varchar(30) NOT NULL,
  `phone_no` varchar(30) NOT NULL,
  `fax_no` varchar(30) NOT NULL,
  `email` varchar(120) NOT NULL,
  `website` varchar(120) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `acc_inflow` (
  `inflow_id` int(11) NOT NULL AUTO_INCREMENT,
  `received_date` date NOT NULL,
  `received_from` varchar(255) NOT NULL,
  `received_type` tinyint(1) NOT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `branch_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(120) DEFAULT NULL,
  `pay_order_number` varchar(120) DEFAULT NULL,
  `letter_of_credit` varchar(120) DEFAULT NULL,
  `deposit_bank_id` int(11) DEFAULT NULL,
  `deposit_branch_name` varchar(120) DEFAULT NULL,
  `deposit_account_name` varchar(120) DEFAULT NULL,
  `deposit_account_number` varchar(120) DEFAULT NULL,
  `amount` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`inflow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `acc_invoice` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `tracking_no` varchar(50) NOT NULL,
  `customer_name` varchar(50) NOT NULL,
  `customer_address` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `billing_address` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `item` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` float NOT NULL,
  `discount` float DEFAULT NULL,
  `vat` float DEFAULT NULL,
  `grand_total` float NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




CREATE TABLE `acc_outflow` (
  `outflow_id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_date` varchar(50) NOT NULL,
  `payment_to` varchar(255) NOT NULL,
  `payment_type` tinyint(1) NOT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `branch_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(120) DEFAULT NULL,
  `pay_order_number` varchar(120) DEFAULT NULL,
  `letter_of_credit` varchar(120) DEFAULT NULL,
  `amount` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date` varchar(50) NOT NULL,
  PRIMARY KEY (`outflow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


