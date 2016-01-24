-- MySQL Workbench Synchronization
-- Generated: 2016-01-24 04:49
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Zab

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `players` 
ADD COLUMN `messages_read` INT(11) NULL DEFAULT 0 COMMENT '' AFTER `gold_expiration`;

ALTER TABLE `bases` 
CHANGE COLUMN `last_update` `last_update` INT(11) NOT NULL DEFAULT 0 COMMENT '' ,
CHANGE COLUMN `shields` `shields` INT(11) NOT NULL DEFAULT 1000 COMMENT '' ,
ADD COLUMN `max_shields` INT(11) NOT NULL DEFAULT 1000 COMMENT '' AFTER `place`,
ADD COLUMN `shield_production` INT(11) NOT NULL DEFAULT 1 COMMENT '' AFTER `max_shields`,
ADD COLUMN `power` INT(11) NOT NULL DEFAULT 1 COMMENT '' AFTER `shield_production`;

ALTER TABLE `base_rooms` 
ADD COLUMN `damage` INT(11) NULL DEFAULT 0 COMMENT '' AFTER `finish_time`,
ADD COLUMN `last_update` INT(11) NULL DEFAULT 0 COMMENT '' AFTER `damage`;

ALTER TABLE `room_types` 
ADD COLUMN `hit_points` INT(11) NULL DEFAULT 1000 COMMENT '' AFTER `can_rotate`,
ADD COLUMN `build_time` INT(11) NULL DEFAULT 300 COMMENT '' AFTER `hit_points`,
ADD COLUMN `build_cost` INT(11) NULL DEFAULT 100000 COMMENT '' AFTER `build_time`,
ADD COLUMN `build_limit` INT(11) NULL DEFAULT 1 COMMENT '' AFTER `build_cost`,
ADD COLUMN `can_land` TINYINT(4) NULL DEFAULT 0 COMMENT '' AFTER `build_limit`,
ADD COLUMN `power` INT(11) NULL DEFAULT 0 COMMENT '' AFTER `can_land`,
ADD COLUMN `good` INT(11) NULL DEFAULT NULL COMMENT '' AFTER `power`,
ADD COLUMN `production` INT(11) NULL DEFAULT 0 COMMENT '' AFTER `good`,
ADD COLUMN `shield_generators` INT(11) NULL DEFAULT 0 COMMENT '' AFTER `production`,
ADD COLUMN `turrets` INT(11) NULL DEFAULT 0 COMMENT '' AFTER `shield_generators`,
ADD COLUMN `turret_damage` INT(11) NULL DEFAULT 0 COMMENT '' AFTER `turrets`,
ADD INDEX `fkey_room_good_idx` (`good` ASC)  COMMENT '';

CREATE TABLE IF NOT EXISTS `research_items` (
  `record_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `caption` VARCHAR(24) NULL DEFAULT NULL COMMENT '',
  `cost` INT(11) NULL DEFAULT 100000 COMMENT '',
  `time` INT(11) NULL DEFAULT 300 COMMENT '',
  `rank` INT(11) NULL DEFAULT NULL COMMENT '',
  `room_unlocked` INT(11) NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`record_id`)  COMMENT '',
  INDEX `fkey_research_room_idx` (`room_unlocked` ASC)  COMMENT '',
  INDEX `fkey_research_rank_idx` (`rank` ASC)  COMMENT '',
  CONSTRAINT `fkey_research_room`
    FOREIGN KEY (`room_unlocked`)
    REFERENCES `room_types` (`record_id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fkey_research_rank`
    FOREIGN KEY (`rank`)
    REFERENCES `ranks` (`record_id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `base_research` (
  `record_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `base` INT(11) NOT NULL COMMENT '',
  `research` INT(11) NOT NULL COMMENT '',
  `finish_time` INT(11) NULL DEFAULT 0 COMMENT '',
  PRIMARY KEY (`record_id`)  COMMENT '',
  INDEX `fkey_research_base_idx` (`base` ASC)  COMMENT '',
  INDEX `fkey_research_item_idx` (`research` ASC)  COMMENT '',
  CONSTRAINT `fkey_research_base`
    FOREIGN KEY (`base`)
    REFERENCES `bases` (`record_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fkey_research_item`
    FOREIGN KEY (`research`)
    REFERENCES `research_items` (`record_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `research_requirements` (
  `record_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `goal` INT(11) NOT NULL COMMENT '',
  `research` INT(11) NULL DEFAULT NULL COMMENT '',
  `build` INT(11) NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`record_id`)  COMMENT '',
  INDEX `fkey_required_goal_idx` (`goal` ASC)  COMMENT '',
  INDEX `fkey_required_research_idx` (`research` ASC)  COMMENT '',
  INDEX `fkey_required_build_idx` (`build` ASC)  COMMENT '',
  CONSTRAINT `fkey_required_goal`
    FOREIGN KEY (`goal`)
    REFERENCES `research_items` (`record_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fkey_required_research`
    FOREIGN KEY (`research`)
    REFERENCES `research_items` (`record_id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fkey_required_build`
    FOREIGN KEY (`build`)
    REFERENCES `room_types` (`record_id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `room_upgrades` (
  `record_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `room` INT(11) NOT NULL COMMENT '',
  `upgrade` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`record_id`)  COMMENT '',
  INDEX `fk_room_upgrade_idx` (`room` ASC)  COMMENT '',
  INDEX `fk_upgrade_room_idx` (`upgrade` ASC)  COMMENT '',
  CONSTRAINT `fk_room_upgrade`
    FOREIGN KEY (`room`)
    REFERENCES `room_types` (`record_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_upgrade_room`
    FOREIGN KEY (`upgrade`)
    REFERENCES `room_types` (`record_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

ALTER TABLE `room_types` 
ADD CONSTRAINT `fkey_room_good`
  FOREIGN KEY (`good`)
  REFERENCES `goods` (`record_id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
