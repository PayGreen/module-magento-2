ALTER TABLE `%{database.entities.button.table}`
    ADD `perCentPayment` INT NULL,
    ADD `subOption` INT DEFAULT 0,
    ADD `reductionPayment` VARCHAR(45) DEFAULT 'none',
    ADD `defaultimg` INT NOT NULL DEFAULT 0
;
