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

-- MySQL dump 10.13  Distrib 8.0.21, for Win64 (x86_64)
--
-- Host: localhost    Database: spacegame_users
-- ------------------------------------------------------
-- Server version	8.0.21

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `record_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(24) NOT NULL,
  `password1` varchar(256) NOT NULL,
  `password2` varchar(256) NOT NULL,
  `session_id` varchar(128) DEFAULT NULL,
  `session_time` int DEFAULT '0',
  `ban_timeout` int DEFAULT '0',
  `ban_code` int DEFAULT '1000',
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Table structure for table `user_fields`
--

DROP TABLE IF EXISTS `user_fields`;
CREATE TABLE `user_fields` (
  `record_id` int NOT NULL AUTO_INCREMENT,
  `user` int DEFAULT NULL,
  `group` varchar(16) NOT NULL,
  `key` varchar(16) NOT NULL,
  `value` varchar(64) NOT NULL,
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `fk_group_key` (`group`,`key`,`user`) USING BTREE,
  KEY `fk_user_flags_idx` (`user`),
  CONSTRAINT `fk_user_flags` FOREIGN KEY (`user`) REFERENCES `users` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Table structure for table `gold_keys`
--

DROP TABLE IF EXISTS `gold_keys`;
CREATE TABLE `gold_keys` (
  `record_id` int NOT NULL AUTO_INCREMENT,
  `type` int NOT NULL DEFAULT '0',
  `key` varchar(96) NOT NULL,
  `time` int NOT NULL DEFAULT '2678400',
  `used` int NOT NULL DEFAULT '0',
  `user` int DEFAULT NULL,
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `gk_UNIQUE` (`key`),
  KEY `fkey_gold_user_idx` (`user`),
  KEY `fkey_gold_type` (`type`),
  KEY `fkey_gold_used` (`used`),
  CONSTRAINT `fkey_gold_user` FOREIGN KEY (`user`) REFERENCES `users` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Table structure for table `login_history`
--

DROP TABLE IF EXISTS `login_history`;
CREATE TABLE `login_history` (
  `record_id` int NOT NULL AUTO_INCREMENT,
  `user` int DEFAULT NULL,
  `ip` varchar(45) NOT NULL,
  `time` int NOT NULL,
  `attempts` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`record_id`),
  KEY `fk_login_user_idx` (`user`),
  CONSTRAINT `fk_login_user` FOREIGN KEY (`user`) REFERENCES `users` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Table structure for table `servers`
--

DROP TABLE IF EXISTS `servers`;
CREATE TABLE `servers` (
  `record_id` int NOT NULL AUTO_INCREMENT,
  `caption` varchar(32) NOT NULL,
  `link` varchar(128) NOT NULL,
  `active_date` int NOT NULL DEFAULT '0',
  `inactive_date` int DEFAULT '0',
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dump completed on 2020-08-06  0:00:00