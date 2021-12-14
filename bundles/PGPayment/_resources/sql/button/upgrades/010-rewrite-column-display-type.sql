ALTER TABLE `%{database.entities.button.table}`
    MODIFY `displayType` VARCHAR(10) NOT NULL DEFAULT 'DEFAULT'
;

# rewrite displayType values
UPDATE `%{database.entities.button.table}`
SET `displayType` = 'PICTURE'
WHERE `displayType` = '1';

UPDATE `%{database.entities.button.table}`
SET `displayType` = 'TEXT'
WHERE `displayType` = '2';

UPDATE `%{database.entities.button.table}`
SET `displayType` = 'DEFAULT'
WHERE `displayType` = '3';

UPDATE `%{database.entities.button.table}`
SET `displayType` = 'HALF'
WHERE `displayType` = 'half';

UPDATE `%{database.entities.button.table}`
SET `displayType` = 'BLOC'
WHERE `displayType` = 'bloc';

UPDATE `%{database.entities.button.table}`
SET `displayType` = 'DEFAULT'
WHERE `displayType` = 'full';
