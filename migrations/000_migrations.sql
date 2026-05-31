--
--  Database migrations
-- --------------------
CREATE TABLE IF NOT EXISTS migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    package VARCHAR(255) NOT NULL,
    migration_file VARCHAR(255) NOT NULL,
    checksum CHAR(40) NOT NULL,
    executed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_migrations_package_file
    UNIQUE (package, migration_file)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;
