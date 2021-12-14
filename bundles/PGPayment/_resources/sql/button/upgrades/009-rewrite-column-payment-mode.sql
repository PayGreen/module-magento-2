ALTER TABLE `%{database.entities.button.table}`
    CHANGE `executedAt` `paymentMode` VARCHAR(10) NOT NULL DEFAULT 'CASH'
;

# rewrite paymentMode values
UPDATE `%{database.entities.button.table}`
SET `paymentMode` = 'CASH'
WHERE `paymentMode` = '0';

UPDATE `%{database.entities.button.table}`
SET `paymentMode` = 'RECURRING'
WHERE `paymentMode` = '3';

UPDATE `%{database.entities.button.table}`
SET `paymentMode` = 'XTIME'
WHERE `paymentMode` = '1';

UPDATE `%{database.entities.button.table}`
SET `paymentMode` = 'TOKENIZE'
WHERE `paymentMode` = '-1';
