ALTER TABLE `%{database.entities.lock.table}`
    CHANGE `lockedAt` `locked_at` INT(11) UNSIGNED NOT NULL
;
