--
--  Database resources
-- -------------------
CREATE TABLE resources (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    resource VARCHAR(100) NOT NULL,
    privilege VARCHAR(100) NOT NULL,
    description VARCHAR(255) NULL,
    UNIQUE KEY uq_resources (resource, privilege)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;
