CREATE TABLE
    IF NOT EXISTS `websession` (
        `code` varchar(128) NOT NULL,
        `content` text NOT NULL,
        `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `update_time` datetime DEFAULT NULL,
        PRIMARY KEY (`code`)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb3