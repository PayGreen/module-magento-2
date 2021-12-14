CREATE TABLE `%{database.entities.recurring_transaction.table}`
(
    `id`                 INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `pid`                VARCHAR(50)      NOT NULL,
    `id_order`           INT(11) UNSIGNED NOT NULL,
    `state`              VARCHAR(50)      NOT NULL,
    `state_order_before` VARCHAR(50)      NOT NULL,
    `state_order_after`  VARCHAR(50)      NULL DEFAULT NULL,
    `mode`               VARCHAR(50)      NOT NULL,
    `amount`             INT(11) UNSIGNED NOT NULL,
    `rank`               INT(5) UNSIGNED NOT NULL,
    `created_at`         INT(11) UNSIGNED NOT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = `utf8`
;

ALTER TABLE `%{database.entities.recurring_transaction.table}`
    ADD UNIQUE (`pid`, `id_order`);
ALTER TABLE `%{database.entities.recurring_transaction.table}`
    ADD UNIQUE (`rank`, `id_order`);
