ALTER TABLE `%{database.entities.button.table}`
    ADD `filtered_category_mode` VARCHAR(50) DEFAULT 'NONE'
;

ALTER TABLE `%{database.entities.button.table}`
    ADD `filtered_category_primaries` VARCHAR(250) DEFAULT 'a:0:{}'
;