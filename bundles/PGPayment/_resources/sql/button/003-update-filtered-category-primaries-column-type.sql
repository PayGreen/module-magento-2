ALTER TABLE `%{database.entities.button.table}`
    MODIFY `filtered_category_mode` VARCHAR(10) DEFAULT 'NONE'
;

ALTER TABLE `%{database.entities.button.table}`
    MODIFY `filtered_category_primaries` TEXT DEFAULT 'a:0:{}'
;