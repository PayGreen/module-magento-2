CREATE TABLE IF NOT EXISTS `%{database.entities.transaction.table}`
(
    `id_cart`    INT(11)      NOT NULL PRIMARY KEY,
    `id_order`   INT(11)      NOT NULL,
    `pid`        VARCHAR(250) NOT NULL,
    `state`      VARCHAR(50)  NOT NULL,
    `type`       VARCHAR(50)  NOT NULL,
    `created_at` INT          NOT NULL,
    `updated_at` INT          NOT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = latin1
;
