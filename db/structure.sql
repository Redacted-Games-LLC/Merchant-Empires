/**
 * Inserts the initial structure into the database
 *
 * @package [Redacted]Me
 * ---------------------------------------------------------------------------
 *
 * Merchant Empires by [Redacted] Games LLC - A space merchant game of war
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
 
-- MySQL dump 10.13  Distrib 5.6.24, for Win64 (x86_64)
-- ------------------------------------------------------
-- Server version	5.6.25-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alliance_invitations`
--

DROP TABLE IF EXISTS `alliance_invitations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alliance_invitations` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `alliance` int(11) NOT NULL,
  `player` int(11) NOT NULL,
  `requested` int(11) NOT NULL DEFAULT '0',
  `rejected` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`record_id`),
  KEY `pk_alliance_invite_idx` (`alliance`),
  KEY `pk_alliance_player_invite_idx` (`player`),
  CONSTRAINT `pk_alliance_invite` FOREIGN KEY (`alliance`) REFERENCES `alliances` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pk_alliance_player_invite` FOREIGN KEY (`player`) REFERENCES `players` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `alliances`
--

DROP TABLE IF EXISTS `alliances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alliances` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(24) NOT NULL,
  `tax_mult` decimal(3,2) DEFAULT '1.00' COMMENT 'Computed from the number of members for speed.',
  `founder` int(11) NOT NULL,
  `recruiting` int(11) DEFAULT '1',
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `caption_UNIQUE` (`caption`),
  KEY `fkey_alliance_founder_idx` (`founder`),
  CONSTRAINT `fkey_alliance_founder` FOREIGN KEY (`founder`) REFERENCES `players` (`record_id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `base_rooms`
--

DROP TABLE IF EXISTS `base_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `base_rooms` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `base` int(11) NOT NULL,
  `room` int(11) NOT NULL,
  `x` int(11) DEFAULT NULL,
  `y` int(11) DEFAULT NULL,
  `theta` int(11) DEFAULT '0' COMMENT '0, 1, 2, or 3 for 90 degree rotations',
  `finish_time` int(11) DEFAULT '0',
  PRIMARY KEY (`record_id`),
  KEY `fkey_room_base_idx` (`base`),
  KEY `fkey_room_type_idx` (`room`),
  CONSTRAINT `fkey_room_base` FOREIGN KEY (`base`) REFERENCES `bases` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fkey_room_type` FOREIGN KEY (`room`) REFERENCES `room_types` (`record_id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bases`
--

DROP TABLE IF EXISTS `bases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bases` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `owner` int(11) DEFAULT NULL,
  `alliance` int(11) DEFAULT NULL,
  `seed` int(11) NOT NULL DEFAULT '0',
  `last_update` int(11) DEFAULT '0',
  `shields` int(11) DEFAULT '0',
  `place` int(11) NOT NULL,
  PRIMARY KEY (`record_id`),
  KEY `fk_base_owner_idx` (`owner`),
  KEY `fk_base_alliance_idx` (`alliance`),
  KEY `fk_base_place_idx` (`place`),
  CONSTRAINT `fk_base_alliance` FOREIGN KEY (`alliance`) REFERENCES `alliances` (`record_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_base_owner` FOREIGN KEY (`owner`) REFERENCES `players` (`record_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_base_place` FOREIGN KEY (`place`) REFERENCES `places` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dealer_inventory`
--

DROP TABLE IF EXISTS `dealer_inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dealer_inventory` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `place` int(11) NOT NULL,
  `item_type` int(11) NOT NULL,
  `item` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT '0',
  `price` int(11) DEFAULT '0',
  `last_update` int(11) DEFAULT '0',
  PRIMARY KEY (`record_id`),
  KEY `fk_dealer_place_idx` (`place`),
  KEY `fk_item_type_idx` (`item_type`),
  CONSTRAINT `fk_dealer_place` FOREIGN KEY (`place`) REFERENCES `places` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_item_type` FOREIGN KEY (`item_type`) REFERENCES `item_types` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=177 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gold_keys`
--

DROP TABLE IF EXISTS `gold_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gold_keys` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL DEFAULT '0',
  `key` varchar(96) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '2678400',
  `used` int(11) NOT NULL DEFAULT '0',
  `user` int(11) DEFAULT NULL,
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `gk_UNIQUE` (`key`),
  KEY `fkey_gold_user_idx` (`user`),
  KEY `fkey_gold_type` (`type`),
  KEY `fkey_gold_used` (`used`),
  CONSTRAINT `fkey_gold_user` FOREIGN KEY (`user`) REFERENCES `users` (`record_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `good_upgrades`
--

DROP TABLE IF EXISTS `good_upgrades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `good_upgrades` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `good` int(11) NOT NULL,
  `target` int(11) NOT NULL,
  PRIMARY KEY (`record_id`),
  KEY `fkey_good_upgrades_idx` (`good`),
  KEY `fkey_good_upgrade_target_idx` (`target`),
  CONSTRAINT `fkey_good_upgrade_source` FOREIGN KEY (`good`) REFERENCES `goods` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fkey_good_upgrade_target` FOREIGN KEY (`target`) REFERENCES `goods` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `goods`
--

DROP TABLE IF EXISTS `goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `goods` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(24) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `race` int(11) DEFAULT NULL,
  `tech` int(11) DEFAULT '0',
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `caption_UNIQUE` (`caption`),
  KEY `goods_level` (`level`),
  KEY `fk_goods_race_idx` (`race`),
  CONSTRAINT `fk_goods_race` FOREIGN KEY (`race`) REFERENCES `races` (`record_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `item_types`
--

DROP TABLE IF EXISTS `item_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_types` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(16) NOT NULL,
  `max_stock` int(11) NOT NULL DEFAULT '15000',
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `caption_UNIQUE` (`caption`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message_ignore`
--

DROP TABLE IF EXISTS `message_ignore`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_ignore` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `player` int(11) NOT NULL,
  `ignore` int(11) NOT NULL,
  `expiration` int(11) DEFAULT NULL,
  PRIMARY KEY (`record_id`),
  KEY `fk_ignore_player_idx` (`player`),
  KEY `fk_player_ignore_idx` (`ignore`),
  KEY `fk_player_ignore_expiration` (`expiration`),
  CONSTRAINT `fk_ignore_player` FOREIGN KEY (`player`) REFERENCES `players` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_player_ignore` FOREIGN KEY (`ignore`) REFERENCES `players` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message_targets`
--

DROP TABLE IF EXISTS `message_targets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_targets` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `message` int(11) NOT NULL,
  `target` int(11) NOT NULL,
  `read` int(11) NOT NULL DEFAULT '0',
  `sender` int(11) DEFAULT NULL,
  PRIMARY KEY (`record_id`),
  KEY `fkey_target_sender_idx` (`sender`),
  KEY `fkey_target_message_idx` (`message`),
  KEY `fkey_target_player_idx` (`target`),
  CONSTRAINT `fkey_target_message` FOREIGN KEY (`message`) REFERENCES `messages` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fkey_target_player` FOREIGN KEY (`target`) REFERENCES `players` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fkey_target_sender` FOREIGN KEY (`sender`) REFERENCES `players` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `posted` int(11) NOT NULL DEFAULT '0',
  `expiration` int(11) NOT NULL DEFAULT '0',
  `message` varchar(512) NOT NULL,
  `sender` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `id` int(11) DEFAULT '0',
  PRIMARY KEY (`record_id`),
  KEY `i_message_timestamp` (`posted`,`expiration`),
  KEY `fk_message_sender_idx` (`sender`),
  CONSTRAINT `fk_message_sender` FOREIGN KEY (`sender`) REFERENCES `players` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `headline` varchar(48) NOT NULL,
  `abstract` varchar(128) NOT NULL,
  `article` text NOT NULL,
  `author` int(11) DEFAULT NULL,
  `live` int(11) DEFAULT '0',
  `archive` int(11) DEFAULT '0',
  `expiration` int(11) DEFAULT '0',
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ordnance`
--

DROP TABLE IF EXISTS `ordnance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ordnance` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `system` int(11) NOT NULL,
  `x` int(11) DEFAULT '0',
  `y` int(11) DEFAULT '0',
  `good` int(11) NOT NULL,
  `amount` int(11) DEFAULT '0',
  `owner` int(11) NOT NULL,
  `alliance` int(11) DEFAULT NULL,
  PRIMARY KEY (`record_id`),
  KEY `fk_ordnance_system_idx` (`system`),
  KEY `fk_ordnance_owner_idx` (`owner`),
  KEY `ordnance_xy` (`y`,`x`),
  KEY `fk_ordnance_alliance_idx` (`alliance`),
  KEY `fk_ordnance_good_idx` (`good`),
  KEY `fk_amount` (`amount`),
  CONSTRAINT `fk_ordnance_alliance` FOREIGN KEY (`alliance`) REFERENCES `alliances` (`record_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_ordnance_good` FOREIGN KEY (`good`) REFERENCES `goods` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ordnance_owner` FOREIGN KEY (`owner`) REFERENCES `players` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ordnance_system` FOREIGN KEY (`system`) REFERENCES `systems` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `place_types`
--

DROP TABLE IF EXISTS `place_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `place_types` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(16) NOT NULL,
  `port_goods` int(11) DEFAULT '0',
  `deploy_solar_collectors` int(11) DEFAULT '0',
  `deploy_bases` int(11) DEFAULT '0',
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `caption_UNIQUE` (`caption`),
  KEY `place_deploy_ports` (`port_goods`),
  KEY `place_deploy_solar` (`deploy_solar_collectors`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `places`
--

DROP TABLE IF EXISTS `places`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `places` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(24) NOT NULL,
  `system` int(11) DEFAULT NULL,
  `x` int(11) NOT NULL DEFAULT '0',
  `y` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `fk_unique_place` (`type`,`y`,`x`),
  KEY `fk_location_places_idx` (`system`),
  KEY `fk_place_type_idx` (`type`),
  KEY `fk_places_xy` (`y`,`x`),
  CONSTRAINT `fk_place_type` FOREIGN KEY (`type`) REFERENCES `place_types` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_system_places` FOREIGN KEY (`system`) REFERENCES `systems` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4287 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `player_cargo`
--

DROP TABLE IF EXISTS `player_cargo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_cargo` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `player` int(11) DEFAULT NULL,
  `good` int(11) DEFAULT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `bought` int(11) DEFAULT '0',
  `sold` int(11) DEFAULT '0',
  PRIMARY KEY (`record_id`),
  KEY `fk_player_cargo_id_idx` (`player`),
  KEY `fk_player_cargo_goods_idx` (`good`),
  CONSTRAINT `fk_player_cargo_goods` FOREIGN KEY (`good`) REFERENCES `goods` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_player_cargo_id` FOREIGN KEY (`player`) REFERENCES `players` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `player_log`
--

DROP TABLE IF EXISTS `player_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_log` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `player` int(11) NOT NULL,
  `action` int(11) NOT NULL,
  `target` int(11) DEFAULT '0',
  `amount1` int(11) DEFAULT '0',
  `amount2` int(11) DEFAULT '0',
  `timestamp` int(11) NOT NULL,
  `reconciled` int(11) DEFAULT '0',
  PRIMARY KEY (`record_id`),
  KEY `fkey_player_log_players_idx` (`player`),
  KEY `idx_player_log_reconciled` (`reconciled`),
  CONSTRAINT `fkey_player_log_players` FOREIGN KEY (`player`) REFERENCES `players` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `players` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(16) NOT NULL,
  `x` int(11) NOT NULL DEFAULT '0',
  `y` int(11) NOT NULL DEFAULT '0',
  `death` int(11) NOT NULL DEFAULT '0' COMMENT 'Timestamp when death has occured or zero if player is still alive.',
  `race` int(11) NOT NULL DEFAULT '0',
  `turns` decimal(6,2) NOT NULL DEFAULT '0.00',
  `ship_type` int(11) DEFAULT NULL,
  `ship_name` varchar(16) DEFAULT NULL,
  `credits` bigint(20) NOT NULL DEFAULT '0',
  `alliance` int(11) DEFAULT NULL,
  `experience` int(11) DEFAULT '0',
  `level` int(11) DEFAULT '0',
  `alignment` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '1',
  `last_turns` int(11) DEFAULT '0',
  `shields` int(11) DEFAULT '0',
  `armor` int(11) DEFAULT '0',
  `last_move` int(11) DEFAULT '0',
  `target_x` int(11) DEFAULT '0',
  `target_y` int(11) DEFAULT '0',
  `target_type` int(11) DEFAULT '0',
  `base_id` int(11) DEFAULT '0',
  `base_x` int(11) DEFAULT '0',
  `base_y` int(11) DEFAULT '0',
  `last_alignment` int(11) DEFAULT '0',
  `gold_expiration` int(11) DEFAULT '0',
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `caption_UNIQUE` (`caption`),
  KEY `fk_player_races_idx` (`race`),
  KEY `fk_ship_type_idx` (`ship_type`),
  KEY `fk_player_xy` (`y`,`x`),
  KEY `fk_player_alliance_idx` (`alliance`),
  KEY `fk_player_rank_idx` (`rank`),
  CONSTRAINT `fk_player_alliance` FOREIGN KEY (`alliance`) REFERENCES `alliances` (`record_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_player_races` FOREIGN KEY (`race`) REFERENCES `races` (`record_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_player_rank` FOREIGN KEY (`rank`) REFERENCES `ranks` (`record_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_ship_type` FOREIGN KEY (`ship_type`) REFERENCES `ships` (`record_id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `port_goods`
--

DROP TABLE IF EXISTS `port_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `port_goods` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `place` int(11) DEFAULT NULL,
  `good` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT '0',
  `distance` int(11) DEFAULT '0',
  `upgrade` int(11) DEFAULT '0',
  `supply` tinyint(1) DEFAULT '0',
  `last_update` int(11) DEFAULT '0',
  PRIMARY KEY (`record_id`),
  KEY `fk_port_good_idx` (`good`),
  KEY `fk_port_place_idx` (`place`),
  CONSTRAINT `fk_port_good` FOREIGN KEY (`good`) REFERENCES `goods` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_port_place` FOREIGN KEY (`place`) REFERENCES `places` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1166 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `races`
--

DROP TABLE IF EXISTS `races`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `races` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(16) NOT NULL,
  `tax_rate` decimal(3,1) NOT NULL DEFAULT '0.1',
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `caption_UNIQUE` (`caption`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ranks`
--

DROP TABLE IF EXISTS `ranks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ranks` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(16) NOT NULL,
  `level` int(11) DEFAULT '0',
  `alignment` int(11) DEFAULT '0',
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `caption_UNIQUE` (`caption`),
  UNIQUE KEY `alignment_UNIQUE` (`alignment`),
  UNIQUE KEY `level_UNIQUE` (`level`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `room_types`
--

DROP TABLE IF EXISTS `room_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room_types` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(24) DEFAULT NULL,
  `width` int(11) DEFAULT '3',
  `height` int(11) DEFAULT '3',
  `floor_mask` bigint(20) DEFAULT '0',
  `wall_mask` bigint(20) DEFAULT '0',
  `can_rotate` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ships`
--

DROP TABLE IF EXISTS `ships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ships` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(24) DEFAULT NULL,
  `race` int(11) NOT NULL,
  `rank` int(11) NOT NULL DEFAULT '1',
  `holds` int(11) NOT NULL DEFAULT '100',
  `shields` int(11) NOT NULL DEFAULT '100',
  `armor` int(11) NOT NULL DEFAULT '100',
  `tps` decimal(3,1) NOT NULL DEFAULT '1.0',
  `price` int(11) DEFAULT '1000000',
  PRIMARY KEY (`record_id`),
  KEY `fk_ship_race_idx` (`race`),
  KEY `idx_ship_level` (`rank`),
  CONSTRAINT `fk_ship_race` FOREIGN KEY (`race`) REFERENCES `races` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ship_rank` FOREIGN KEY (`rank`) REFERENCES `ranks` (`record_id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `start_goods`
--

DROP TABLE IF EXISTS `start_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `start_goods` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `place_type` int(11) NOT NULL,
  `good` int(11) NOT NULL,
  `percent` int(11) NOT NULL DEFAULT '100',
  `supply` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`record_id`),
  KEY `fkey_start_goods_place_idx` (`place_type`),
  KEY `fkey_start_goods_idx` (`good`),
  CONSTRAINT `fkey_start_goods` FOREIGN KEY (`good`) REFERENCES `goods` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fkey_start_goods_place` FOREIGN KEY (`place_type`) REFERENCES `place_types` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `systems`
--

DROP TABLE IF EXISTS `systems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `systems` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(24) NOT NULL,
  `x` int(11) NOT NULL DEFAULT '0',
  `y` int(11) NOT NULL DEFAULT '0',
  `radius` int(11) NOT NULL DEFAULT '8',
  `protected` tinyint(1) DEFAULT '0',
  `race` int(11) DEFAULT '0',
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `caption_UNIQUE` (`caption`),
  UNIQUE KEY `system_xy` (`y`,`x`),
  KEY `fk_system_race_idx` (`race`),
  CONSTRAINT `fk_system_race` FOREIGN KEY (`race`) REFERENCES `races` (`record_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=345 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_fields`
--

DROP TABLE IF EXISTS `user_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_fields` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `group` varchar(16) NOT NULL,
  `key` varchar(16) NOT NULL,
  `value` varchar(64) NOT NULL,
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `fk_group_key` (`group`,`key`,`user`) USING BTREE,
  KEY `fk_user_flags_idx` (`user`),
  CONSTRAINT `fk_user_flags` FOREIGN KEY (`user`) REFERENCES `users` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_players`
--

DROP TABLE IF EXISTS `user_players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_players` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `player` int(11) DEFAULT NULL,
  `session_id` varchar(128) DEFAULT NULL,
  `session_time` int(11) DEFAULT '0',
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `idx_user_player` (`user`,`player`),
  KEY `fk_up_players_idx` (`player`),
  CONSTRAINT `fk_up_players` FOREIGN KEY (`player`) REFERENCES `players` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_up_users` FOREIGN KEY (`user`) REFERENCES `users` (`record_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(24) NOT NULL,
  `password1` varchar(256) NOT NULL,
  `password2` varchar(256) NOT NULL,
  `session_id` varchar(128) DEFAULT NULL,
  `session_time` int(11) DEFAULT '0',
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `warps`
--

DROP TABLE IF EXISTS `warps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warps` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `place` int(11) NOT NULL,
  `x` int(11) DEFAULT NULL COMMENT 'Destination',
  `y` int(11) DEFAULT NULL COMMENT 'Destination',
  PRIMARY KEY (`record_id`),
  KEY `fkey_warp_place_idx` (`place`),
  CONSTRAINT `fkey_warp_place` FOREIGN KEY (`place`) REFERENCES `places` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping events for database 'spacegame'
--

--
-- Dumping routines for database 'spacegame'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-01-17 17:49:25
