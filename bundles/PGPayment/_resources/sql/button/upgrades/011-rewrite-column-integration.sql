ALTER TABLE `%{database.entities.button.table}`
    MODIFY `integration` VARCHAR(10) NOT NULL DEFAULT 'EXTERNAL'
;

# rewrite integration values
UPDATE `%{database.entities.button.table}`
SET `integration` = 'EXTERNAL'
WHERE `integration` = '0';

UPDATE `%{database.entities.button.table}`
SET `integration` = 'INSITE'
WHERE `integration` = '1';
