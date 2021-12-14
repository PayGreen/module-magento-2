
CREATE TABLE `%{database.entities.carbon_data.table}`
(
  `id`              int(11)         UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_order`        int(10)         UNSIGNED NOT NULL,
  `id_user`         int(10)         UNSIGNED NOT NULL,
  `id_fingerprint`  varchar(100)    NOT NULL,
  `footprint`       float           NOT NULL,
  `carbon_offset`   float           NOT NULL,
  `created_at`      int(10)         UNSIGNED NOT NULL
) ENGINE = `%{db.var.engine}`
  DEFAULT CHARSET = `utf8`
;

CREATE UNIQUE INDEX `UX_ID_ORDER` ON `%{database.entities.carbon_data.table}` (`id_order`);
