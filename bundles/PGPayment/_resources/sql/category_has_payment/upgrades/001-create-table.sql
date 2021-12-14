CREATE TABLE `%{database.entities.category_has_payment.table}`
(
    `id`          INT(11)     NOT NULL AUTO_INCREMENT,
    `id_category` INT(11)     NOT NULL,
    `payment`     VARCHAR(50) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = utf8
;