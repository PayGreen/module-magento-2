DROP TABLE IF EXISTS `%{database.entities.fingerprint.table}`;

CREATE TABLE `%{database.entities.fingerprint.table}`
(
    `id`          INT(11)      UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `session`     VARCHAR(100) NOT NULL,
    `browser`     VARCHAR(255) NOT NULL,
    `device`      VARCHAR(255) NOT NULL,
    `pages`       INT(6)       UNSIGNED NOT NULL,
    `pictures`    INT(6)       UNSIGNED NOT NULL,
    `time`        INT(11)      UNSIGNED NOT NULL,
    `created_at`  DATETIME     NOT NULL
) ENGINE = `%{db.var.engine}`DEFAULT CHARSET = `utf8`;

ALTER TABLE `%{database.entities.fingerprint.table}`
    ADD UNIQUE (`session`)
;
