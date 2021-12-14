CREATE TABLE `%{database.entities.button.table}`
(
    `id`                          INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `paymentType`                 VARCHAR(50)      NOT NULL DEFAULT 'CB',
    `image`                       VARCHAR(250)              DEFAULT NULL,
    `height`                      INT(5)                    DEFAULT NULL,
    `position`                    INT(3)           NOT NULL DEFAULT 1,
    `displayType`                 VARCHAR(10)      NOT NULL DEFAULT 'DEFAULT',
    `paymentNumber`               INT(5)  UNSIGNED NOT NULL DEFAULT 1,
    `firstPaymentPart`            INT(5)  UNSIGNED          DEFAULT NULL,
    `orderRepeated`               INT(1)  UNSIGNED NOT NULL DEFAULT 0,
    `discount`                    INT(11) UNSIGNED NOT NULL DEFAULT 0,
    `minAmount`                   DECIMAL(10, 2)            DEFAULT NULL,
    `maxAmount`                   DECIMAL(10, 2)            DEFAULT NULL,
    `filtered_category_mode`      VARCHAR(10)      NOT NULL DEFAULT 'NONE',
    `filtered_category_primaries` TEXT,
    `paymentMode`                 VARCHAR(10)      NOT NULL DEFAULT 'CASH',
    `paymentReport`               VARCHAR(15)               DEFAULT NULL,
    `id_shop`                     INT(11) UNSIGNED NOT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = `utf8`
;