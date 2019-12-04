CREATE TABLE IF NOT EXISTS `%{db.var.prefix}paygreen_buttons`
(
  `id`               INT              NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `label`            VARCHAR(100)     NULL,
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
  `paymentMode`      VARCHAR(10)      NOT NULL DEFAULT 'CASH',
  `paymentReport`    VARCHAR(15)               DEFAULT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = `utf8`
;

CREATE TABLE IF NOT EXISTS `%{db.var.prefix}paygreen_transactions`
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

ALTER TABLE `%{db.var.prefix}paygreen_transactions`
  ADD UNIQUE (`pid`, `id_order`)
;

CREATE TABLE IF NOT EXISTS `%{db.var.prefix}paygreen_recurring_transaction`
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

ALTER TABLE `%{db.var.prefix}paygreen_recurring_transaction`
  ADD UNIQUE (`pid`, `id_order`);
ALTER TABLE `%{db.var.prefix}paygreen_recurring_transaction`
  ADD UNIQUE (`rank`, `id_order`);

CREATE TABLE IF NOT EXISTS `%{db.var.prefix}paygreen_fingerprint`
(
  `fingerprint` varchar(100) NOT NULL,
  `key`         varchar(255) NOT NULL,
  `value`       varchar(255) NOT NULL,
  `createdAt`   datetime     NOT NULL,
  `index`       varchar(255) NOT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = `utf8`
;

CREATE TABLE IF NOT EXISTS `%{db.var.prefix}paygreen_transaction_locks`
(
  `id`        int              NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `pid`       varchar(100)     NOT NULL,
  `locked_at` INT(11) UNSIGNED NOT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = `utf8`
;

ALTER TABLE `%{db.var.prefix}paygreen_transaction_locks`
  ADD UNIQUE (`pid`)
;

CREATE TABLE IF NOT EXISTS `%{db.var.prefix}paygreen_categories_has_payments`
(
  `id`          int(11)     NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_category` int(11)     NOT NULL,
  `payment`     varchar(50) NOT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = `utf8`
;

