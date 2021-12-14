CREATE TABLE `%{database.entities.lock.table}`
(
    `id`        INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `pid`       VARCHAR(100)     NOT NULL,
    `locked_at` INT(11) UNSIGNED NOT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = `utf8`
;

ALTER TABLE `%{database.entities.lock.table}`
    ADD UNIQUE (`pid`)
;
