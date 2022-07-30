--
--  Netis, Little cms - Database
-- -----------------------------

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) unsigned NOT NULL,
  `resource_id` int(11) unsigned NOT NULL,
  `privilege_id` int(11) unsigned NOT NULL,
  `allowed` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_id_resource_id_privilege_id` (`role_id`,`resource_id`,`privilege_id`),
  KEY `resource` (`resource_id`),
  KEY `role` (`role_id`),
  KEY `privilege` (`privilege_id`),
  CONSTRAINT `permissions_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`),
  CONSTRAINT `permissions_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `permissions_ibfk_3` FOREIGN KEY (`privilege_id`) REFERENCES `privileges` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

INSERT INTO `permissions` (`id`, `role_id`, `resource_id`, `privilege_id`, `allowed`) VALUES
(1,	1,	1,	1,	1),
(2,	1,	2,	2,	1),
(3,	1,	3,	1,	1),
(4,	3,	2,	1,	1);

DROP VIEW IF EXISTS `permissions_roles_view`;
CREATE TABLE `permissions_roles_view` (`id` int(11) unsigned, `name` varchar(40), `parent` int(11));


DROP VIEW IF EXISTS `permissions_view`;
CREATE TABLE `permissions_view` (`id` int(11) unsigned, `resource` varchar(40), `privilege` varchar(40), `role` varchar(40), `allowed` tinyint(1));


DROP TABLE IF EXISTS `privileges`;
CREATE TABLE `privileges` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

INSERT INTO `privileges` (`id`, `name`) VALUES
(1,	'*all'),
(2,	'default'),
(3,	'submit');

DROP TABLE IF EXISTS `resources`;
CREATE TABLE `resources` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

INSERT INTO `resources` (`id`, `name`) VALUES
(1,	'Front:Home'),
(2,	'Backend:Admin'),
(3,	'Backend:Sign'),
(4,	'Backend:Access');

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `parent` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

INSERT INTO `roles` (`id`, `name`, `parent`) VALUES
(1,	'guest',	0),
(2,	'member',	1),
(3,	'admin',	2);

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `name` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `users_roles`;
CREATE TABLE `users_roles` (
  `role_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `role_id_user_id` (`role_id`,`user_id`),
  KEY `user` (`user_id`),
  KEY `role` (`role_id`),
  CONSTRAINT `users_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `users_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP VIEW IF EXISTS `users_roles_view`;
CREATE TABLE `users_roles_view` (`user_id` int(11) unsigned, `username` varchar(50), `role` varchar(40));


DROP TABLE IF EXISTS `permissions_roles_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `permissions_roles_view` AS select `roles`.`id` AS `id`,`roles`.`name` AS `name`,`roles`.`parent` AS `parent` from `roles` where `roles`.`id` in (select distinct `permissions`.`role_id` from `permissions` where `roles`.`name` <> 'admin');

DROP TABLE IF EXISTS `permissions_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `permissions_view` AS select `p`.`id` AS `id`,`r`.`name` AS `resource`,`p2`.`name` AS `privilege`,`r2`.`name` AS `role`,`p`.`allowed` AS `allowed` from (((`permissions` `p` left join `resources` `r` on(`p`.`resource_id` = `r`.`id`)) left join `privileges` `p2` on(`p`.`privilege_id` = `p2`.`id`)) left join `roles` `r2` on(`p`.`role_id` = `r2`.`id`));

DROP TABLE IF EXISTS `users_roles_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `users_roles_view` AS select `u`.`id` AS `user_id`,`u`.`username` AS `username`,`r`.`name` AS `role` from ((`users_roles` `ur` left join `roles` `r` on(`r`.`id` = `ur`.`role_id`)) left join `users` `u` on(`u`.`id` = `ur`.`user_id`));
