ALTER TABLE `%{database.entities.button.table}`
    MODIFY `image` VARCHAR(250) DEFAULT NULL,
    DROP `defaultimg`
;
