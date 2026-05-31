--
--  Database authorization
--  ----------------------
CREATE TABLE authorization (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    role_id INT UNSIGNED NOT NULL,
    resource_id INT UNSIGNED NOT NULL,

    PRIMARY KEY (id),
    UNIQUE KEY uq_authorization_role_resource (role_id, resource_id),

    CONSTRAINT fk_authorization_role
        FOREIGN KEY (role_id)
            REFERENCES roles(id)
            ON DELETE CASCADE
            ON UPDATE CASCADE,

    CONSTRAINT fk_authorization_resource
        FOREIGN KEY (resource_id)
            REFERENCES resources(id)
            ON DELETE CASCADE
            ON UPDATE CASCADE
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;
