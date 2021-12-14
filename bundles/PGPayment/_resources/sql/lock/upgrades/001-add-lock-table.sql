CREATE TABLE `%{database.entities.lock.table}`
(
  `id`       INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `pid`      VARCHAR(100) NOT NULL,
  `lockedAt` INT          NULL DEFAULT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = utf8
;

ALTER TABLE `%{database.entities.lock.table}`
  ADD UNIQUE (`pid`)
;
