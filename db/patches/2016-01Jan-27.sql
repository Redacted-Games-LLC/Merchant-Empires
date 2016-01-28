
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `research_items` 
DROP FOREIGN KEY `fkey_research_rank`,
DROP FOREIGN KEY `fkey_research_room`;

ALTER TABLE `room_upgrades` 
DROP FOREIGN KEY `fk_upgrade_room`;

ALTER TABLE `room_types` 
ADD COLUMN `turn_cost` INT(11) NULL DEFAULT 1 COMMENT '' AFTER `build_limit`,
ADD COLUMN `experience` INT(11) NULL DEFAULT 0 COMMENT '' AFTER `turret_damage`;

ALTER TABLE `research_items` 
DROP COLUMN `room_unlocked`,
CHANGE COLUMN `rank` `rank` INT(11) NOT NULL DEFAULT 1 COMMENT '' ,
ADD COLUMN `turn_cost` INT(11) NULL DEFAULT 1 COMMENT '' AFTER `cost`,
ADD COLUMN `experience` INT(11) NULL DEFAULT 0 COMMENT '' AFTER `rank`,
DROP INDEX `fkey_research_room_idx` ;

ALTER TABLE `research_requirements` 
ADD COLUMN `good` INT(11) NULL DEFAULT NULL COMMENT '' AFTER `build`,
ADD COLUMN `amount` INT(11) NULL DEFAULT 0 COMMENT '' AFTER `good`,
ADD INDEX `fkey_required_good_idx` (`good` ASC)  COMMENT '';

ALTER TABLE `room_upgrades` 
CHANGE COLUMN `upgrade` `build` INT(11) NULL DEFAULT NULL COMMENT '' ,
ADD COLUMN `research` INT(11) NULL DEFAULT NULL COMMENT '' AFTER `build`,
ADD COLUMN `good` INT(11) NULL DEFAULT NULL COMMENT '' AFTER `research`,
ADD COLUMN `amount` INT(11) NULL DEFAULT 0 COMMENT '' AFTER `good`,
ADD INDEX `fk_room_research_idx` (`research` ASC)  COMMENT '',
ADD INDEX `fk_room_good_idx` (`good` ASC)  COMMENT '', RENAME TO  `room_requirements` ;

ALTER TABLE `research_items` 
ADD CONSTRAINT `fkey_research_rank`
  FOREIGN KEY (`rank`)
  REFERENCES `ranks` (`record_id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;

ALTER TABLE `research_requirements` 
ADD CONSTRAINT `fkey_required_good`
  FOREIGN KEY (`good`)
  REFERENCES `goods` (`record_id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE;

ALTER TABLE `room_upgrades` 
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
  ON UPDATE CASCADE;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;



