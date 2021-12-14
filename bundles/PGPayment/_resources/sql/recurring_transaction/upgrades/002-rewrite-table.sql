ALTER TABLE `%{database.entities.recurring_transaction.table}`
    ADD     `id_order`                  INT(10) UNSIGNED NOT NULL DEFAULT 0,
    ADD     `state_order_before`        VARCHAR(50)      NOT NULL DEFAULT '',
    ADD     `created_at`                INT(10) UNSIGNED NOT NULL DEFAULT 0
;

UPDATE `%{database.entities.recurring_transaction.table}` SET `created_at` = UNIX_TIMESTAMP(`date_payment`);

ALTER TABLE `%{database.entities.recurring_transaction.table}`
    DROP PRIMARY KEY,
    MODIFY  `id`                 INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    MODIFY  `pid`                VARCHAR(50)      NOT NULL AFTER `id`,
    MODIFY  `id_order`           INT(10) UNSIGNED NOT NULL AFTER `pid`,
    MODIFY  `state`              VARCHAR(50)      NOT NULL AFTER `id_order`,
    MODIFY  `state_order_before` VARCHAR(50)      NOT NULL AFTER `state`,
    ADD     `state_order_after`  VARCHAR(50)      NULL DEFAULT NULL AFTER `state_order_before`,
    CHANGE  `type` `mode`        VARCHAR(50)      NOT NULL AFTER `state_order_after`,
    MODIFY  `amount`             INT(10) UNSIGNED NOT NULL AFTER `mode`,
    MODIFY  `rank`               INT(10) UNSIGNED NOT NULL AFTER `amount`,
    MODIFY  `created_at`         INT(10) UNSIGNED NOT NULL AFTER `rank`,
    DROP    `date_payment`,
    ADD UNIQUE (`pid`, `id_order`),
    ADD UNIQUE (`rank`, `id_order`)
;
