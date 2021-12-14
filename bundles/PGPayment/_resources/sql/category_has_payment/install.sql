CREATE TABLE `%{database.entities.category_has_payment.table}`
(
    `id`          INT(11)     UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_category` INT(11)     UNSIGNED NOT NULL,
    `payment`     VARCHAR(50) NOT NULL,
    `id_shop`     INT(11)     UNSIGNED NOT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = `utf8`
;
