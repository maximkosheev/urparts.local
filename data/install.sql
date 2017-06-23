--
-- Database: `urparts.local`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(20) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `firstname` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lastname` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` VARCHAR(20) NOT NULL,
  `city` VARCHAR(100) NULL,
  `country` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `tbl_category`
--
CREATE TABLE IF NOT EXISTS `tbl_category` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `tbl_manufacture`
--

CREATE TABLE IF NOT EXISTS `tbl_manufacture` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for `tbl_category_manufacture`
--

CREATE TABLE IF NOT EXISTS `tbl_catman` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `category_id` INT(11) NOT NULL,
  `manufacture_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY(`category_id`, `manufacture_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `tbl_model`
--

CREATE TABLE IF NOT EXISTS `tbl_model` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `catman_id` INT(11) NOT NULL,
  `title` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`catman_id`, `title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `tbl_order`
--
CREATE TABLE IF NOT EXISTS `tbl_order` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `status` INT NOT NULL DEFAULT 0,
  `user_id` INT(11) NOT NULL,
  `manufacture_id` INT(11) NULL,
  `manufacture_name` VARCHAR(50) DEFAULT NULL,
  `model_id` INT(11) NULL,
  `model_name` VARCHAR(50) DEFAULT NULL,
  `group_id` INT(11) NOT NULL,
  `part_no` VARCHAR(255),
  `part_description` VARCHAR(255) NOT NULL,
  `create_dt` DATETIME NOT NULL,
  `update_dt` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `tbl_groups`
--
CREATE TABLE IF NOT EXISTS `tbl_group`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(50) NOT NULL,
  `maillist` VARCHAR(255) NULL DEFAULT "",
  PRIMARY KEY (`id`),
  UNIQUE KEY (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `tbl_feedback`
--
CREATE TABLE IF NOT EXISTS `tbl_feedback` (
  `subject` VARCHAR(100) NOT NULL,
  `user_name` VARCHAR(20) NOT NULL,
  `user_email` VARCHAR(20) NOT NULL,
  `user_phone` VARCHAR(100) NOT NULL,
  `user_country` VARCHAR(100) NOT NULL,
  `text` TEXT,
  `create_dt` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tbl_model`
  ADD CONSTRAINT `fk_catman` FOREIGN KEY (`catman_id`) REFERENCES `tbl_catman`(`id`) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE `tbl_catman`
  ADD CONSTRAINT `fk_category` FOREIGN KEY(`category_id`) REFERENCES `tbl_category`(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  ADD CONSTRAINT `fk_manufacture` FOREIGN KEY(`manufacture_id`) REFERENCES `tbl_manufacture`(`id`) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE `tbl_order`
    ADD CONSTRAINT `fk_order_user` FOREIGN KEY(`user_id`) REFERENCES `tbl_user`(`id`) ON UPDATE CASCADE ON DELETE RESTRICT,
    ADD CONSTRAINT `fk_order_manufacture` FOREIGN KEY(`manufacture_id`) REFERENCES `tbl_manufacture`(`id`) ON UPDATE CASCADE ON DELETE SET NULL,
    ADD CONSTRAINT `fk_order_model` FOREIGN KEY(`model_id`) REFERENCES `tbl_model`(`id`) ON UPDATE CASCADE ON DELETE SET NULL,
    ADD CONSTRAINT `fk_order_group` FOREIGN KEY(`group_id`) REFERENCES `tbl_group`(`id`) ON UPDATE CASCADE ON DELETE RESTRICT;