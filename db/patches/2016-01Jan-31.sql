

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `research_items` 
DROP FOREIGN KEY `fkey_research_rank`;

ALTER TABLE `room_types` 
CHANGE COLUMN `turn_cost` `turn_cost` DECIMAL(6,2) NULL DEFAULT 1 ;

ALTER TABLE `weapons` 
ADD COLUMN `good` INT(11) NOT NULL DEFAULT 0 AFTER `caption`,
ADD INDEX `fkey_weapon_good_idx` (`good` ASC);

CREATE TABLE IF NOT EXISTS `solutions` (
  `record_id` INT(11) NOT NULL AUTO_INCREMENT,
  `player` INT(11) NOT NULL,
  `weapon` INT(11) NOT NULL,
  `ship` INT(11) NOT NULL,
  `group` INT(11) NOT NULL DEFAULT 0,
  `sequence` INT(11) NOT NULL DEFAULT 0,
  `fire_time` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`record_id`),
  INDEX `fk_solution_player_idx` (`player` ASC),
  INDEX `fk_solution_weapon_idx` (`weapon` ASC),
  INDEX `fk_solution_ship_idx` (`ship` ASC),
  CONSTRAINT `fk_solution_player`
    FOREIGN KEY (`player`)
    REFERENCES `players` (`record_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_solution_weapon`
    FOREIGN KEY (`weapon`)
    REFERENCES `weapons` (`record_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_solution_ship`
    FOREIGN KEY (`ship`)
    REFERENCES `ships` (`record_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

ALTER TABLE `research_items` 
ADD CONSTRAINT `fkey_research_rank`
  FOREIGN KEY (`rank`)
  REFERENCES `ranks` (`record_id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;

ALTER TABLE `weapons` 
ADD CONSTRAINT `fkey_weapon_good`
  FOREIGN KEY (`good`)
  REFERENCES `goods` (`record_id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;

INSERT INTO `goods` (`caption`, `level`, `tech`) VALUES ('Newbie Laser', '10', '100000');
ALTER TABLE `weapons` CHANGE COLUMN `caption` `caption` VARCHAR(24) NOT NULL ;
INSERT INTO `weapons` (`caption`, `good`, `racks`, `stations`, `accuracy`, `volley`, `ammunition`, `general_damage`, `shield_damage`, `armor_damage`) VALUES ('Newbie Laser', '63', '0', '1', '1.0', '1', '1', '20', '0', '0');
UPDATE `item_types` SET `max_stock`='5000' WHERE `caption`='Goods';
ALTER TABLE `goods` ADD COLUMN `type` INT(11) NULL DEFAULT 0 AFTER `tech`;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;







