
CREATE TABLE `%{database.entities.setting.table}`
(
    `id`       int(11)      UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_shop`  int(11)      UNSIGNED NULL DEFAULT NULL,
    `name`     varchar(50)  NOT NULL,
    `value`    text         NOT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = utf8
;
