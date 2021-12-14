ALTER TABLE `%{database.entities.button.table}`
    MODIFY `id`                          INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    MODIFY `paymentType`                 VARCHAR(50)      NOT NULL DEFAULT 'CB',
    MODIFY `height`                      INT(5)                    DEFAULT NULL,
    MODIFY `position`                    INT(3)           NOT NULL DEFAULT 1,
    MODIFY `orderRepeated`               INT(1)  UNSIGNED NOT NULL DEFAULT 0,
    MODIFY `filtered_category_mode`      VARCHAR(10)      NOT NULL DEFAULT 'NONE'
;