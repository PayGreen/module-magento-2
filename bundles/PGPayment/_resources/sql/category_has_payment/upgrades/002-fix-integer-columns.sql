ALTER TABLE `%{database.entities.category_has_payment.table}`
    MODIFY `id`          INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    MODIFY `id_category` INT(11) UNSIGNED NOT NULL
;