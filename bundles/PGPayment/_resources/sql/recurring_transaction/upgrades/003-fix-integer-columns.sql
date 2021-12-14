ALTER TABLE `%{database.entities.recurring_transaction.table}`
    MODIFY  `id`                 INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    MODIFY  `id_order`           INT(11) UNSIGNED NOT NULL,
    MODIFY  `amount`             INT(11) UNSIGNED NOT NULL,
    MODIFY  `rank`               INT(5) UNSIGNED NOT NULL,
    MODIFY  `created_at`         INT(11) UNSIGNED NOT NULL
;