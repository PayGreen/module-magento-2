ALTER TABLE `%{database.entities.button.table}`
    CHANGE `nbPayment` `paymentNumber` INT(5) UNSIGNED NOT NULL DEFAULT 1,
    CHANGE `perCentPayment` `firstPaymentPart` INT(5) UNSIGNED DEFAULT NULL,
    CHANGE `subOption` `orderRepeated` INT(1) UNSIGNED DEFAULT 0,
    CHANGE `reportPayment` `paymentReport` VARCHAR(15) DEFAULT NULL
;
