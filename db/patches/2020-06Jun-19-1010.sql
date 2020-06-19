
ALTER TABLE `players` 
ADD COLUMN `unread_messages` TINYINT(1) NULL DEFAULT '0' AFTER `armor_bonus`;

ALTER TABLE `message_targets` 
ADD COLUMN `hidden` TINYINT(1) NULL DEFAULT '0' AFTER `sender`;
