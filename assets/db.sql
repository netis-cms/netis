--
--  Netis, Little CMS - Database
-- -----------------------------

-- ---- create table:
CREATE TABLE `menu_category` (
  `categoryId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(30) NOT NULL,
  PRIMARY KEY (`categoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ---- insert values to table:
INSERT INTO `menu_category` (`categoryId`, `category`) VALUES
(1, 'category.system');


-- ---- create table:
CREATE TABLE `menu` (
  `menuId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `categoryId` int(11) unsigned NOT NULL,
  `parent` int(11) NOT NULL,
  `link` char(30) NOT NULL,
  `name` char(30) NOT NULL,
  `icon` char(30) NOT NULL,
  PRIMARY KEY (`menuId`),
  KEY `category` (`categoryId`),
  CONSTRAINT `menu` FOREIGN KEY (`categoryId`)
  REFERENCES `menu_category` (`categoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ---- insert values to table:
INSERT INTO `menu` (`menuId`, `categoryId`, `parent`, `link`, `name`, `icon`) VALUES
(1, 1, '0', ':Admin:Admin:main', 'menu.admin', 'fa fa-home'),
(2, 1, '0', ':Admin:Settings:web', 'menu.settings', 'fa fa-cog');


-- ---- create table:
CREATE TABLE `settings` (
  `name` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ---- insert values to table:
INSERT INTO `settings` (`name`, `value`) VALUES
('website', 'Netis'),
('description', 'CMS');


-- ---- create table:
CREATE TABLE `users` (
  `userId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `realname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` char(60) NOT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ---- insert values to table:
INSERT INTO `users` (`userId`, `realname`, `email`, `password`) VALUES
(1,	'Admin',	'admin@local',	'$2y$10$Gqo0SEC.DUEhEpRt//kpOum2ki7ueECrI/jGBEnI/sfPXOe4ZYeoC');
