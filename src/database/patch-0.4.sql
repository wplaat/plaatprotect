--
--  ============
--  PlaatProtect
--  ============
--
--  Created by wplaat
--
--  For more information visit the following website.
--  Website : www.plaatsoft.nl 
--
--  Or send an email to the following address.
--  Email   : info@plaatsoft.nl
--
--  All copyrights reserved (c) 1996-2019 PlaatSoft
--

UPDATE config SET value="0.4" WHERE token='database_version';

-- Central structure improvement
ALTER TABLE `session` CHANGE `sid` `sid` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `event` CHANGE `eid` `eid` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `config` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;

-- Cron table
ALTER TABLE `cron` ADD `every_x_mins` INT NOT NULL AFTER `last_run`;
UPDATE `cron` SET `every_x_mins` = '1440' WHERE `cron`.`cid` = 1;
INSERT INTO cron (`cid`, `note`, `last_run`, `every_x_mins`) VALUES ('2', 'hue_luminance_sensors', '2019-08-31 00:00:00', 5);
INSERT INTO cron (`cid`, `note`, `last_run`, `every_x_mins`) VALUES ('3', 'hue_motion_sensors', '2019-08-31 00:00:00', 1);
INSERT INTO cron (`cid`, `note`, `last_run`, `every_x_mins`) VALUES ('4', 'hue_temperature_sensors', '2019-08-31 00:00:00', 5);

-- Config table
INSERT INTO config (category, token, value, options, date, readonly, rebuild, encrypt) VALUES (0, 'enable_battery_view', 'true', 'true,false', '0000-00-00', 0, 0, 0);
INSERT INTO config (category, token, value, options, date, readonly, rebuild, encrypt) VALUES (0, 'enable_temperature_view', 'true', 'true,false', '0000-00-00', 0, 0, 0);
INSERT INTO config (category, token, value, options, date, readonly, rebuild, encrypt) VALUES (0, 'enable_luminance_view', 'true', 'true,false', '0000-00-00', 0, 0, 0);
INSERT INTO config (category, token, value, options, date, readonly, rebuild, encrypt) VALUES (0, 'enable_humidity_view', 'false', 'true,false', '0000-00-00', 0, 0, 0);
INSERT INTO config (category, token, value, options, date, readonly, rebuild, encrypt) VALUES (0, 'enable_motion_view', 'true', 'true,false', '0000-00-00', 0, 0, 0);
INSERT INTO config (category, token, value, options, date, readonly, rebuild, encrypt) VALUES (0, 'system_name', '', '', '0000-00-00', 0, 0, 0);
INSERT INTO config (category, token, value, options, date, readonly, rebuild, encrypt) VALUES (51, 'home_username', '', '', '0000-00-00', 0, 0, 0);
DELETE FROM config WHERE category = 52;

-- zigbee table
ALTER TABLE hue RENAME TO zigbee;
ALTER TABLE `zigbee` CHANGE `type` `type` INT(32) NOT NULL;
ALTER TABLE `zigbee` CHANGE `hid` `zid` INT(11) NOT NULL;
ALTER TABLE `zigbee` DROP `panic`;
ALTER TABLE `zigbee` DROP `away`;
ALTER TABLE `zigbee` DROP `sleep`;
ALTER TABLE `zigbee` DROP `home`;

UPDATE config set token="zigbee_present" WHERE token="hue_present";
UPDATE config set token="zigbee_description" WHERE token="hue_description";
UPDATE config set token="zigbee_ip_address" WHERE token="hue_ip_address";
UPDATE config set token="zigbee_key" WHERE token="hue_key";

-- sensor table
ALTER TABLE `sensor` ADD `value` DOUBLE NOT NULL AFTER `battery`;
ALTER TABLE `sensor` ADD INDEX(`timestamp`);
ALTER TABLE `sensor` ADD INDEX(`zid`);
UPDATE sensor SET value = luminance where luminance>0;
UPDATE sensor SET value = temperature where temperature>0;
ALTER TABLE `sensor` DROP `temperature`;
ALTER TABLE `sensor` DROP `luminance`;
ALTER TABLE `sensor` DROP `humidity`;
ALTER TABLE `sensor` DROP `ultraviolet`;
ALTER TABLE `sensor` DROP `battery`;

-- Actor Table
ALTER TABLE notification RENAME TO actor;
ALTER TABLE `actor` CHANGE `nid` `aid` INT(11) NOT NULL;
UPDATE `actor` SET `aid` = '101' WHERE `actor`.`aid` = 1;
UPDATE `actor` SET `aid` = '102' WHERE `actor`.`aid` = 2;
UPDATE `actor` SET `aid` = '103' WHERE `actor`.`aid` = 3;
ALTER TABLE `actor` CHANGE `aid` `aid` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `actor` ADD `vendor` VARCHAR(255) NOT NULL AFTER `aid`;
ALTER TABLE `actor` ADD `location` VARCHAR(255) NOT NULL AFTER `type`;
ALTER TABLE `actor` ADD `version` VARCHAR(255) NOT NULL AFTER `vendor`;
UPDATE `actor` SET `vendor` = '', `version` = '', `location` = 'Cloud' WHERE `actor`.`aid` = 101;
UPDATE `actor` SET `vendor` = '', `version` = '', `location` = 'Cloud' WHERE `actor`.`aid` = 102;
UPDATE `actor` SET `vendor` = '', `version` = '', `location` = '?' WHERE `actor`.`aid` = 103;
