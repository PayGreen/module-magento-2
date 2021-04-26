CREATE TABLE `%{database.entities.button.table}`
(
    `id`               INT              NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `paymentType`      VARCHAR(50)               DEFAULT 'CB',
    `image`            VARCHAR(250)              DEFAULT NULL,
    `height`           INT              NULL,
    `position`         INT              NULL     DEFAULT 1,
    `displayType`      VARCHAR(10)      NOT NULL DEFAULT 'DEFAULT',
    `integration`      VARCHAR(10)      NOT NULL DEFAULT 'EXTERNAL',
    `paymentNumber`    INT(5) UNSIGNED  NOT NULL DEFAULT 1,
    `firstPaymentPart` INT(5) UNSIGNED           DEFAULT NULL,
    `orderRepeated`    INT(1) UNSIGNED           DEFAULT 0,
    `discount`         INT(11) UNSIGNED NOT NULL DEFAULT 0,
    `minAmount`        DECIMAL(10, 2)   NULL,
    `maxAmount`        DECIMAL(10, 2)   NULL,
    `filtered_category_mode` VARCHAR(10) DEFAULT 'NONE',
    `filtered_category_primaries` TEXT,
    `paymentMode`      VARCHAR(10)      NOT NULL DEFAULT 'CASH',
    `paymentReport`    VARCHAR(15)               DEFAULT NULL,
    `id_shop`          int(11)              UNSIGNED NOT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = `utf8`
;

CREATE TABLE `%{database.entities.transaction.table}`
(
    `id`         int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `pid`        varchar(50)      NOT NULL,
    `id_order`   int(10) UNSIGNED NOT NULL,
    `state`      varchar(50)      NOT NULL,
    `mode`       varchar(50)      NOT NULL,
    `amount`     int(10) UNSIGNED NOT NULL,
    `created_at` int(10) UNSIGNED NOT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = `utf8`
;

ALTER TABLE `%{database.entities.transaction.table}`
    ADD UNIQUE (`pid`, `id_order`)
;

CREATE TABLE `%{database.entities.recurring_transaction.table}`
(
    `id`                 int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `pid`                varchar(50)      NOT NULL,
    `id_order`           int(10) UNSIGNED NOT NULL,
    `state`              varchar(50)      NOT NULL,
    `state_order_before` varchar(50)      NOT NULL,
    `state_order_after`  varchar(50)      NULL DEFAULT NULL,
    `mode`               varchar(50)      NOT NULL,
    `amount`             int(10) UNSIGNED NOT NULL,
    `rank`               int(10) UNSIGNED NOT NULL,
    `created_at`         int(10) UNSIGNED NOT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = `utf8`
;

ALTER TABLE `%{database.entities.recurring_transaction.table}`
    ADD UNIQUE (`pid`, `id_order`);
ALTER TABLE `%{database.entities.recurring_transaction.table}`
    ADD UNIQUE (`rank`, `id_order`);

CREATE TABLE `%{database.entities.fingerprint.table}`
(
    `id`          int(11)      UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `session`     varchar(100) NOT NULL,
    `browser`     varchar(255) NOT NULL,
    `device`      varchar(255) NOT NULL,
    `pages`       int(6)       UNSIGNED NOT NULL,
    `pictures`    int(6)       UNSIGNED NOT NULL,
    `time`        int(11)      UNSIGNED NOT NULL,
    `created_at`  datetime     NOT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = `utf8`
;

ALTER TABLE `%{database.entities.fingerprint.table}`
    ADD UNIQUE (`session`)
;

CREATE TABLE `%{database.entities.lock.table}`
(
    `id`        int              NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `pid`       varchar(100)     NOT NULL,
    `locked_at` INT(11) UNSIGNED NOT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = `utf8`
;

ALTER TABLE `%{database.entities.lock.table}`
    ADD UNIQUE (`pid`)
;

CREATE TABLE `%{database.entities.category_has_payment.table}`
(
    `id`          int(11)     NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_category` int(11)     NOT NULL,
    `payment`     varchar(50) NOT NULL,
    `id_shop`     int(11)     UNSIGNED NOT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = `utf8`
;
