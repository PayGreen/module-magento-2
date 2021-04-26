
ALTER TABLE `%{database.entities.button.table}`
    CHANGE `id_shop` `id_shop` int(11) UNSIGNED NOT NULL
;

ALTER TABLE `%{database.entities.category_has_payment.table}`
    CHANGE `id_shop` `id_shop` int(11) UNSIGNED NOT NULL
;


DROP TABLE IF EXISTS `%{database.entities.fingerprint.table}`;

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
