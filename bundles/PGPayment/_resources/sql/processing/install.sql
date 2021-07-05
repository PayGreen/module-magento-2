
CREATE TABLE `%{database.entities.processing.table}`
(
    `id`         int(11)        UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_shop`    varchar(100)   NOT NULL,
    `reference`  varchar(250)   NOT NULL,
    `success`    int(1)         UNSIGNED DEFAULT 0,
    `status`     varchar(50)    NOT NULL,
    `pid`        varchar(100)   NOT NULL,
    `pid_status` varchar(50)    NOT NULL,
    `created_at` int(11)        UNSIGNED NOT NULL,
    `echoes`     text           NOT NULL,
    `amount`     int(11)        NOT NULL,
    `id_order`   varchar(100)   NULL DEFAULT NULL,
    `state_from` varchar(50)    NULL DEFAULT NULL,
    `state_to`   varchar(50)    NULL DEFAULT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = utf8
;
