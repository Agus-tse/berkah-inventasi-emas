ALTER TABLE `#__miniorange_tfa_settings` ADD COLUMN  `mo_tfa_for_roles` varchar(2048)  NOT NULL DEFAULT '["ALL"]';