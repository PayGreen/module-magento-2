CREATE TABLE IF NOT EXISTS `%{database.entities.recurring_transaction.table}`
(
    `id`           INT(11)      NOT NULL,
    `rank`         INT(11)      NOT NULL,
    `pid`          VARCHAR(250) NOT NULL,
    `amount`       INT(11)      NOT NULL,
    `state`        VARCHAR(50)  NOT NULL,
    `type`         VARCHAR(50)  NOT NULL,
    `date_payment` DATE         NOT NULL,
    PRIMARY KEY (`id`, `rank`)
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = utf8
;