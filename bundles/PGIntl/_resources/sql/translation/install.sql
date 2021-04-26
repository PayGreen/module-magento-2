
CREATE TABLE `%{database.entities.translation.table}`
(
    `id`       INT(11)      UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_shop`  INT(11)      UNSIGNED NOT NULL,
    `code`     VARCHAR(100) NOT NULL,
    `language` VARCHAR(45)  NOT NULL,
    `text`     MEDIUMTEXT   NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = utf8
;
