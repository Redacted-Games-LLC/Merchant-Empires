<?php
/**
 * 
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

	include_once('inc/events.php');
	include_once('inc/goods.php');
	include_once('inc/place_types.php');
	include_once('inc/galaxy.php');
	
	register_event(new Event_Upgrade_Ports());

	class Event_Upgrade_Ports extends Event {

		private $upgrade_paths = array();
		private $reverse_upgrade_paths = array();
		private $port_place_types = array();

		public function __construct() {

			global $db;
			$db = isset($db) ? $db : new DB;

			global $spacegame;

			foreach ($spacegame['place_types'] as $type => $row) {
				if ($row['port_goods'] > 0) {
					$this->port_place_types[$type] = $row['port_goods'];
					$place_types[] = $type;
				}
			}

			$place_type_string = join(',', $place_types);

			$upgrade_paths = array();
			$reverse_upgrade_paths = array();

			$rs = $db->get_db()->query("select * from good_upgrades order by good, target");

			if (!$rs || !$rs->data_seek(0)) {
				error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				return;
			}

			while ($row = $rs->fetch_assoc()) {
				$upgrade_paths[$row['good']]['targets'][] = $row['target'];
				$upgrade_paths[$row['good']]['count'] = isset($upgrade_paths[$row['good']]['count']) ? $upgrade_paths[$row['good']]['count'] + 1 : 1;

				$reverse_upgrade_paths[$row['target']]['goods'][] = $row['good'];
				$reverse_upgrade_paths[$row['target']]['count'] = isset($reverse_upgrade_paths[$row['target']]['count']) ? $reverse_upgrade_paths[$row['target']]['count'] + 1 : 1;
			}

			$this->upgrade_paths = $upgrade_paths;
			$this->reverse_upgrade_paths = $reverse_upgrade_paths;
		}

		public function getRunTime() {
			return PORT_EVENT_CYCLE + 3;
		}

		public function run() {

			$this->incrementRun();

			global $db;
			$db = isset($db) ? $db : new DB;

			global $spacegame;

			// Grab ports which have no upgrades and get them starter upgrades.
			$ports_without_upgrades = array();

			$rs = $db->get_db()->query("select p1.record_id, p2.type from places as p1 left join places as p2 on p1.x = p2.x and p1.y = p2.y, place_types as t1, place_types as t2 where p1.type = t1.record_id and t1.caption = 'Port' and p2.type = t2.record_id and t2.port_goods > 0 and 0 >= (select count(*) from port_goods where upgrade > 0 and place = p1.record_id)");

			if (!$rs) {
				error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				return;
			}

			$rs->data_seek(0);
			while ($row = $rs->fetch_assoc()) {
				$ports_without_upgrades[$row['record_id']] = $row;
			}
			
			foreach ($ports_without_upgrades as $place_id => $row) {
				$this->add_upgrades_to_port($place_id, $this->port_place_types[$row['type']] + PLACE_TYPE_UPGRADE_OFFSET);
			}

			// Now grab ports with finished upgrades and facilitate them

			$ports_with_upgrades = array();

			$rs = $db->get_db()->query("select place, upgrade, sum(amount) as total from port_goods as p1 where upgrade > 0 and upgrade not in (select good from port_goods as p2 where p1.place = p2.place and p1.upgrade = p2.good and p2.supply > 0) group by place, upgrade having total = 0");

			if (!$rs) {
				error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				return;
			}

			if (!$rs->data_seek(0)) {
				// No places to upgrade. Return false.
				return;
			}

			while ($row = $rs->fetch_assoc()) {
				$ports_with_upgrades[$row['place']] = $row['upgrade'];
			}

			// Loop through finished ports and add the new good.
			$goods_list = array();
			$goods_count = 0;
			$supply_start_amount = PORT_LIMIT;

			foreach ($ports_with_upgrades as $place_id => $upgrade) {

				// Remove existing demands now that there is a supply

				if (!($st = $db->get_db()->prepare("delete from port_goods where place = ? and good = ? and supply = 0"))) {
					$return_codes[] = 1006;
					return;
				}
				
				$st->bind_param("ii", $place_id, $upgrade);
				
				if (!$st->execute()) {
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					return;
				}

				// Insert new supply

				if (!($st = $db->get_db()->prepare("insert into port_goods (place, good, amount, supply, upgrade, last_update) values (?,?,?,1,0,0)"))) {
					$return_codes[] = 1006;
					return;
				}
				
				$st->bind_param("iii", $place_id, $upgrade, $supply_start_amount);
				
				if (!$st->execute()) {
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					return;
				}

				$goods_list[] = $upgrade;
				$goods_count++;
			}

			if ($goods_count > 0) {
				update_distances($goods_list);
			}

			// Now add a replacement upgrade to the port.
			$this->add_upgrades_to_port($place_id, 1);
		}

		private function add_upgrades_to_port($place_id, $upgrade_count) {

			global $db;
			$db = isset($db) ? $db : new DB;

			global $spacegame;

			$time = time();

			// Grab a list of supplies for this place.
			$supplies = array();
			$existing_upgrades = array();

			$rs = $db->get_db()->query("select good, upgrade, supply from port_goods where (supply > 0 or upgrade > 0) and place = '$place_id'");

			if (!$rs || !$rs->data_seek(0)) {
				error_log(__FILE__ . '::' . __LINE__ . " Query failed. You may have a broken port with no supply or demand goods.");
				return false;
			}

			while ($row = $rs->fetch_assoc()) {
				if ($row['upgrade'] > 0 && $row['supply'] <= 0) {
					$existing_upgrades[$row['upgrade']] = $row;
				}
				else if ($row['upgrade'] <= 0 && $row['supply'] > 0) {
					$supplies[$row['good']] = $row;
				}
			}

			// Attempt to search for possible upgrades. This looks convoluted but
			// the idea is that to be eligible for an upgrade a port must have at
			// least one of the required goods for sale already but not all of
			// them. We must then make sure we don't go over the max and still have
			// some randomness by skipping. If we random over everything we need to
			// start over but also limit these restarts to prevent endless loops.

			$upgrades = array();

			$upgrade_paths = $this->upgrade_paths;
			$reverse_upgrade_paths = $this->reverse_upgrade_paths;

			$loop_limit = 1000;

			while ($upgrade_count > 0 && $loop_limit > 0) {
				$loop_limit--;

				foreach ($supplies as $good => $row) {

					if (!isset($upgrade_paths[$good])) {
						continue;
					}

					if ($upgrade_paths[$good]['count'] <= 0) {
						continue;
					}

					$targets = array();

					foreach ($upgrade_paths[$good]['targets'] as $target) {

						if (isset($existing_upgrades[$target])) {
							continue;
						}
						
						if (isset($upgrades[$target])) {
							continue;
						}

						if ($reverse_upgrade_paths[$target]['count'] <= 0) {
							continue;
						}

						if (mt_rand(0, 100) < UPGRADE_CHANCE) {
							continue;
						}

						// Reverse search targets to find all goods we may be able to 
						// demand for an upgrade.

						$success = false;

						foreach ($reverse_upgrade_paths[$target]['goods'] as $other_good) {

							if ($good == $other_good) {
								continue;
							}

							if (isset($supplies[$other_good])) {
								continue;
							}

							$upgrades[$target][] = $other_good;
							$success = true;
						}

						if ($success) {
							$upgrade_count--;

							if ($upgrade_count <= 0) {
								break 3;
							}
						}
					}
				}
			}

			// We should have a list of upgrades we can add to the port. Systematically add
			// them and update distances for all goods involved.
			
			$good_list = array();
			$good_count = 0;
			$demand_start_amount = UPGRADE_START_MULTIPLIER;

			if (!($st = $db->get_db()->prepare("insert into port_goods (place, good, amount, supply, upgrade, last_update) values (?,?,?,0,?,?)"))) {
				$return_codes[] = 1006;
				return;
			}
			
			$st->bind_param("iiiii", $place_id, $good, $upgrade_start_amount, $target, $time);

			foreach ($upgrades as $target => $list) {

				$good_list[] = $target;
				$good_count++;

				foreach ($list as $good) {

					$upgrade_start_amount = $demand_start_amount * $spacegame['goods'][$target]['level'];

					if (!$st->execute()) {
						error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
						return;
					}

					$good_list[] = $good;
					$good_count++;
				}
			}

			if ($good_count > 0) {
				update_distances($good_list);
			}
			else {
				if (mt_rand(0, 1000) < 100) {
					update_distances();
				}
			}
		}
	};