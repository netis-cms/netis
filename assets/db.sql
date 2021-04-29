--
--  Netis, Little cms - Database
-- -----------------------------

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
