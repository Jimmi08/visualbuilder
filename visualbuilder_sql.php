CREATE TABLE qoobbuilder_data (
  `id` int(10) NOT NULL AUTO_INCREMENT,
	`entity_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'The entity id this data is attached to.',
	`entity_type` varchar(50) NOT NULL DEFAULT '' COMMENT 'The entity type this data is attached to.',
	`entity_filename` varchar(50) NOT NULL DEFAULT '',
	`entity_data` text NULL,
	PRIMARY KEY (`id`),
	KEY `entity_id` (`entity_id`),
	KEY `entity_type` (`entity_type`)
) ENGINE=MyISAM;
