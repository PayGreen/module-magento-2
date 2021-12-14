ALTER TABLE `%{database.entities.transaction.table}`
    DROP PRIMARY KEY,
    ADD `id`             INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT PRIMARY KEY,
    ADD `amount`         INT(10) UNSIGNED    NOT NULL DEFAULT 0,
    MODIFY `pid`         VARCHAR(50)         NOT NULL,
    MODIFY `id_order`    INT(10) UNSIGNED    NOT NULL,
    MODIFY `state`       VARCHAR(50)         NOT NULL,
    CHANGE `type` `mode` VARCHAR(50)         NOT NULL,
    MODIFY `amount`      INT(10) UNSIGNED    NOT NULL,
    MODIFY `created_at`  INT(10) UNSIGNED    NOT NULL,
    DROP `updated_at`,
    ADD UNIQUE (`pid`, `id_order`)
;
