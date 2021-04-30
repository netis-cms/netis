--
--  Netis, Little cms - Database
-- -----------------------------

-- ---- database settings:
SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

-- ---- create table:
CREATE TABLE `settings` (
    `name` varchar(100) NOT NULL,
    `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ---- create table:
CREATE TABLE `users` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL,
    `email` varchar(50) NOT NULL,
    `password` varchar(60) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ---- create table:
CREATE TABLE `authorization` (
    `role_id` int(11) unsigned NOT NULL,
    `user_id` int(11) unsigned NOT NULL,
     KEY `user` (`user_id`),
     KEY `role` (`role_id`),
     CONSTRAINT `acl_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
     CONSTRAINT `acl_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ---- create view:
CREATE TABLE `authorization_view` (`role` varchar(40));

-- ---- create table:
CREATE TABLE `permissions` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `role_id` int(11) unsigned NOT NULL,
    `resource_id` int(11) unsigned NOT NULL,
    `privilege_id` int(11) unsigned NOT NULL,
    `allowed` enum('no','yes') NOT NULL DEFAULT 'yes',
    PRIMARY KEY (`id`),
    KEY `resource` (`resource_id`),
    KEY `role` (`role_id`),
    KEY `privilege` (`privilege_id`),
    CONSTRAINT `permissions_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`),
    CONSTRAINT `permissions_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
    CONSTRAINT `permissions_ibfk_3` FOREIGN KEY (`privilege_id`) REFERENCES `privileges` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ---- create view:
CREATE TABLE `permissions_roles_view` (
    `id` int(11) unsigned,
    `name` varchar(40),
    `parent` int(11)
);

-- ---- create view:
CREATE TABLE `permissions_view` (
    `id` int(11) unsigned,
    `resource` varchar(40),
    `privilege` varchar(40),
    `role` varchar(40),
    `allowed` enum('no','yes')
);

-- ---- create table:
CREATE TABLE `privileges` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(40) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ---- insert values to table:
INSERT INTO `privileges` (`id`, `name`) VALUES
(1,	'*all'),
(2,	'default'),
(2,	'submit');

-- ---- create table:
CREATE TABLE `resources` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(40) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ---- insert values to table:
INSERT INTO `resources` (`id`, `name`) VALUES
(1,	'Web:Web'),
(2,	'Admin:Admin'),
(3,	'Admin:Sign');

-- ---- create table:
CREATE TABLE `roles` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(40) NOT NULL,
    `parent` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ---- insert values to table:
INSERT INTO `roles` (`id`, `name`, `parent`) VALUES
(1,	'guest',	0),
(2,	'member',	1);

-- ---- create trigger:
DELIMITER ;;

CREATE TRIGGER `insert` BEFORE INSERT ON `roles` FOR EACH ROW
BEGIN
    IF (new.name = 'admin') THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'This name is not allowed for the role.';
    END IF;
END;;

CREATE TRIGGER `update` BEFORE UPDATE ON `roles` FOR EACH ROW
BEGIN
    IF (new.name = 'admin') THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'The role is not allowed to change to this name.';
    END IF;
END;;

DELIMITER ;

-- ---- create query:
DROP TABLE IF EXISTS `authorization_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `authorization_view` AS select `r`.`name` AS `role` from (`authorization` `a` left join `roles` `r` on(`a`.`role_id` = `r`.`id`));

-- ---- create query:
DROP TABLE IF EXISTS `permissions_roles_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `permissions_roles_view` AS select `roles`.`id` AS `id`,`roles`.`name` AS `name`,`roles`.`parent` AS `parent`
from `roles` where `roles`.`id` in (select distinct `permissions`.`role_id` from `permissions` where `roles`.`name` <> 'admin');

-- ---- create query:
DROP TABLE IF EXISTS `permissions_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `permissions_view` AS select `p`.`id` AS `id`,`r`.`name` AS `resource`,`p2`.`name` AS `privilege`,`r2`.`name` AS `role`,`p`.`allowed` AS `allowed`
from (((`permissions` `p`
    left join `resources` `r` on(`p`.`resource_id` = `r`.`id`))
    left join `privileges` `p2` on(`p`.`privilege_id` = `p2`.`id`))
    left join `roles` `r2` on(`p`.`role_id` = `r2`.`id`));
