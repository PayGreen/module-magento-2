CREATE TABLE `%{database.entities.transaction.table}`
(
    `id`         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `pid`        VARCHAR(50)      NOT NULL,
    `id_order`   INT(11) UNSIGNED NOT NULL,
    `state`      VARCHAR(50)      NOT NULL,
    `mode`       VARCHAR(50)      NOT NULL,
    `amount`     INT(11) UNSIGNED NOT NULL,
    `created_at` INT(11) UNSIGNED NOT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = `utf8`
;

ALTER TABLE `%{database.entities.transaction.table}`
    ADD UNIQUE (`pid`, `id_order`)
;
