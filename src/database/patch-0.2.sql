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
--  All copyrights reserved (c) 2008-2016 PlaatSoft
--

UPDATE config SET value="0.2" WHERE token='database_version';

INSERT INTO config (category, token, value, options, date, readonly, rebuild, encrypt) VALUES (0, 'alarm_scenario', '0', '', '0000-00-00', '1', '0', '0');
INSERT INTO config (category, token, value, options, date, readonly, rebuild, encrypt) VALUES (0, 'alarm_duration', '30', '', '0000-00-00', '0', '0', '0');
INSERT INTO config (category, token, value, options, date, readonly, rebuild, encrypt) VALUES (0, 'device_offline_timeout', '900', '', '0000-00-00', '0', '0', '0');
INSERT INTO config (category, token, value, options, date, readonly, rebuild, encrypt) VALUES (21, 'email_present', 'no', 'yes,no', '0000-00-00', '0', '0', '0');
INSERT INTO config (category, token, value, options, date, readonly, rebuild, encrypt) VALUES (21, 'email_address', '', '', '0000-00-00', '0', '0', '0');
UPDATE config SET token='mobile_present' WHERE token="notification_present";
UPDATE config SET token='mobile_nma_key' WHERE token="notification_nma_key";
