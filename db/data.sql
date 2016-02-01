/**
 * Inserts the initial data into the database
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


DELETE FROM `races`;
INSERT INTO `races` (`record_id`,`caption`,`tax_rate`) VALUES (1,'Xollian',0.0);
INSERT INTO `races` (`record_id`,`caption`,`tax_rate`) VALUES (2,'Mawlor',4.5);
INSERT INTO `races` (`record_id`,`caption`,`tax_rate`) VALUES (3,'Zyck\'lirg',2.0);

DELETE FROM `ranks`;
INSERT INTO `ranks` (`record_id`,`caption`,`level`,`alignment`) VALUES (1,'Civilian',0,0);
INSERT INTO `ranks` (`record_id`,`caption`,`level`,`alignment`) VALUES (2,'Cadet',5,1);
INSERT INTO `ranks` (`record_id`,`caption`,`level`,`alignment`) VALUES (3,'Ensign',10,10);
INSERT INTO `ranks` (`record_id`,`caption`,`level`,`alignment`) VALUES (4,'Lieutenant',20,50);
INSERT INTO `ranks` (`record_id`,`caption`,`level`,`alignment`) VALUES (5,'Commander',30,100);
INSERT INTO `ranks` (`record_id`,`caption`,`level`,`alignment`) VALUES (6,'Captain',40,200);
INSERT INTO `ranks` (`record_id`,`caption`,`level`,`alignment`) VALUES (7,'Commodore',60,300);
INSERT INTO `ranks` (`record_id`,`caption`,`level`,`alignment`) VALUES (8,'Admiral',80,400);
INSERT INTO `ranks` (`record_id`,`caption`,`level`,`alignment`) VALUES (9,'Grand Admiral',100,500);

DELETE FROM `item_types`;
INSERT INTO `item_types` (`record_id`,`caption`,`max_stock`) VALUES (1,'Ships',100);
INSERT INTO `item_types` (`record_id`,`caption`,`max_stock`) VALUES (2,'Goods',5000);
INSERT INTO `item_types` (`record_id`,`caption`,`max_stock`) VALUES (3,'Personnel',50);

DELETE FROM `place_types`;
INSERT INTO `place_types` (`record_id`,`caption`,`port_goods`,`deploy_solar_collectors`) VALUES (1,'Ship Dealer',0,0);
INSERT INTO `place_types` (`record_id`,`caption`,`port_goods`,`deploy_solar_collectors`) VALUES (2,'Star',0,1);
INSERT INTO `place_types` (`record_id`,`caption`,`port_goods`,`deploy_solar_collectors`) VALUES (3,'Earth Planet',3,0);
INSERT INTO `place_types` (`record_id`,`caption`,`port_goods`,`deploy_solar_collectors`) VALUES (5,'Rocky Planet',2,0);
INSERT INTO `place_types` (`record_id`,`caption`,`port_goods`,`deploy_solar_collectors`) VALUES (6,'Goods Trader',0,0);
INSERT INTO `place_types` (`record_id`,`caption`,`port_goods`,`deploy_solar_collectors`) VALUES (7,'Solar Collector',0,0);
INSERT INTO `place_types` (`record_id`,`caption`,`port_goods`,`deploy_solar_collectors`) VALUES (8,'Port',0,0);
INSERT INTO `place_types` (`record_id`,`caption`,`port_goods`,`deploy_solar_collectors`) VALUES (9,'Warp',0,0);
INSERT INTO `place_types` (`record_id`,`caption`,`port_goods`,`deploy_solar_collectors`) VALUES (11,'Tech Dealer',0,0);
INSERT INTO `place_types` (`record_id`,`caption`,`port_goods`,`deploy_solar_collectors`) VALUES (12,'Ice Giant',3,0);

DELETE FROM `ships`;
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (1,'Initiate',1,1,100,125,125,1.0,1000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (2,'Recruit',2,1,100,150,200,1.5,1000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (3,'Hatchling',3,1,100,150,100,1.0,1000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (4,'Supply Ship',2,2,250,300,400,2.0,5000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (5,'Corvette',2,3,100,350,450,3.0,7500000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (6,'Galactic Mover',2,4,400,350,450,3.5,15000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (7,'Cruiser',2,5,50,650,500,4.0,25000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (8,'Retribution',2,6,500,500,500,4.5,35000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (9,'Dreadnought',2,7,150,750,750,5.0,45000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (10,'Destiny Seeker',1,2,200,250,200,1.5,5000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (11,'Blind Side',1,3,150,300,300,2.0,7500000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (12,'Venom',1,3,50,400,100,1.5,7500000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (13,'Specter',1,4,500,300,400,3.0,15000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (14,'Predator',1,4,50,500,300,2.0,15000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (15,'Dark Aura',1,5,100,700,400,3.0,25000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (16,'Occult Blade',1,6,600,500,400,3.5,35000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (17,'Chaos Prophet',1,7,200,700,600,4.0,45000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (18,'Parasite',3,2,25,150,125,1.0,1000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (19,'Stellar Leech',3,3,200,225,250,2.0,5000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (20,'Drone',3,3,25,200,250,1.5,5000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (21,'Locust',3,2,50,100,200,2.0,5000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (22,'Pestilence',3,3,250,250,300,2.5,7500000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (23,'Cluster Guard',3,4,50,400,300,2.0,7500000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (24,'Planetary Scourge',3,4,450,400,400,3.0,15000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (25,'Swarm Leader',3,5,100,500,600,3.5,25000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (26,'Tarantula',3,6,550,400,500,3.5,35000000);
INSERT INTO `ships` (`record_id`,`caption`,`race`,`rank`,`holds`,`shields`,`armor`,`tps`,`price`) VALUES (27,'Black Widow',3,7,50,650,800,4.0,45000000);















