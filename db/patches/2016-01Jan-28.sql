-- MySQL Workbench Synchronization
-- Generated: 2016-01-28 18:01
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Zab

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE TABLE IF NOT EXISTS `room_requirements` (
  `record_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `room` INT(11) NOT NULL COMMENT '',
  `build` INT(11) NULL DEFAULT NULL COMMENT '',
  `research` INT(11) NULL DEFAULT NULL COMMENT '',
  `good` INT(11) NULL DEFAULT NULL COMMENT '',
  `amount` INT(11) NULL DEFAULT 0 COMMENT '',
  PRIMARY KEY (`record_id`)  COMMENT '',
  INDEX `fk_room_upgrade_idx` (`room` ASC)  COMMENT '',
  INDEX `fk_upgrade_room_idx` (`build` ASC)  COMMENT '',
  INDEX `fk_room_research_idx` (`research` ASC)  COMMENT '',
  INDEX `fk_room_good_idx` (`good` ASC)  COMMENT '',
  CONSTRAINT `fk_room_upgrade`
    FOREIGN KEY (`room`)
    REFERENCES `room_types` (`record_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_upgrade_room`
    FOREIGN KEY (`build`)
    REFERENCES `room_types` (`record_id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_room_research`
    FOREIGN KEY (`research`)
    REFERENCES `base_research` (`record_id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_room_good`
    FOREIGN KEY (`good`)
    REFERENCES `goods` (`record_id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

ALTER TABLE `research_requirements` 
ADD CONSTRAINT `fkey_required_good`
  FOREIGN KEY (`good`)
  REFERENCES `goods` (`record_id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE;

ALTER TABLE `room_requirements` 
ADD CONSTRAINT `fk_upgrade_room`
  FOREIGN KEY (`build`)
  REFERENCES `room_types` (`record_id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE,
ADD CONSTRAINT `fk_room_research`
  FOREIGN KEY (`research`)
  REFERENCES `base_research` (`record_id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE,
ADD CONSTRAINT `fk_room_good`
  FOREIGN KEY (`good`)
  REFERENCES `goods` (`record_id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
