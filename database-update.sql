SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
ALTER TABLE `bp_admin_groups` DROP COLUMN `server`;
ALTER TABLE `bp_admins` ADD COLUMN `aid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`aid`);
ALTER TABLE `bp_admins` DROP INDEX `pid_sid`, ADD INDEX `pid` (`pid`);
ALTER TABLE `bp_admin_groups` ADD COLUMN `isadmin` INT(1) NOT NULL DEFAULT '0' AFTER `usetime`;