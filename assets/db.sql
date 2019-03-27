--
--  Netis, Little CMS - Database
-- -----------------------------

-- ---- create table:
CREATE TABLE `menu_category` (
  `categoryId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(30) NOT NULL,
  PRIMARY KEY (`categoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ---- create table:
CREATE TABLE `menu` (
  `menuId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `categoryId` int(11) unsigned NOT NULL,
  `link` char(30) NOT NULL,
  `name` char(30) NOT NULL,
  PRIMARY KEY (`menuId`),
  KEY `category` (`categoryId`),
  CONSTRAINT `menu` FOREIGN KEY (`categoryId`)
  REFERENCES `menu_category` (`categoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ---- create table:
CREATE TABLE `settings` (
  `name` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ---- create table:
CREATE TABLE `users` (
  `userId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `realname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` char(60) NOT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
