CREATE TABLE IF NOT EXISTS `%{database.entities.button.table}` (
    `id`          INT NOT NULL AUTO_INCREMENT,
    `label`       VARCHAR(100) NULL,
    `image`       VARCHAR(45) NULL,
    `height`      INT NULL,
    `position`    INT NULL,
    `displayType` VARCHAR(45) NULL DEFAULT 'default',
    `nbPayment`   INT NOT NULL DEFAULT 1,
    `minAmount`   DECIMAL(10,2) NULL,
    `maxAmount`   DECIMAL(10,2) NULL,
    PRIMARY KEY (`id`)
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = `utf8`
;
