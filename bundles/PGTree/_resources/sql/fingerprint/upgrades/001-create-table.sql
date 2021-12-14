CREATE TABLE `%{database.entities.fingerprint.table}` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `fingerprint` VARCHAR(30) NOT NULL,
    `key` VARCHAR(50) NOT NULL,
    `value` VARCHAR(50) NOT NULL,
    `createdAt` DATE NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE='InnoDB' DEFAULT CHARSET=`utf8`;
