ALTER TABLE `%{database.entities.button.table}`
    CHANGE `reductionPayment` `discount` VARCHAR(45) DEFAULT NULL
;

# rewrite discount values
UPDATE `%{database.entities.button.table}`
SET `discount` = '0'
WHERE `discount` = 'none'
   OR `discount` = ''
   OR `discount` IS NULL
;

ALTER TABLE `%{database.entities.button.table}`
    CHANGE `discount` `discount` INT(11) UNSIGNED NOT NULL DEFAULT 0
;
