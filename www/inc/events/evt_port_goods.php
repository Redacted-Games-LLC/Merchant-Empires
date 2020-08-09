<?php
/**
 * Updates port trade and dealer stock levels.
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
	
	register_event(new Event_Port_Goods());

	class Event_Port_Goods extends Event {

		public function getRunTime() {
			return PORT_EVENT_CYCLE;
		}

		public function run() {

			$this->incrementRun();

			global $db;
			$db = isset($db) ? $db : new DB;

			$time = time();

			// Ports first

			$update_ports = array();
			$update_ports_count = 0;

			$rs = $db->get_db()->query("select * from port_goods where upgrade = 0 and last_update < " . ($time - PORT_UPDATE_TIME) . " and ((supply = 0 and amount > '". (-PORT_LIMIT) ."') or (supply = 1 and amount < '". (PORT_LIMIT) ."')) order by last_update limit " . PORTS_PER_UPDATE);
			
			$rs->data_seek(0);
			while ($row = $rs->fetch_assoc()) {
				$update_ports[$row['record_id']] = $row;
				$update_ports_count += 1;
			}

			//echo "Found $update_ports_count record(s) to update...\n";

			foreach ($update_ports as $id => $row) {

				$goods_to_update = floor(($time - $row['last_update']) * GOODS_PER_UPDATE / PORT_UPDATE_TIME);
				
				//echo "Updating good record $id with up to $goods_to_update goods.\n";

				if ($row['supply'] <= 0) {
					$amount = max(-PORT_LIMIT, $row['amount'] - $goods_to_update);
				}
				elseif ($row['supply'] > 0) {
					$amount = min(PORT_LIMIT, $row['amount'] + $goods_to_update);

					$rs = $db->get_db()->query("select count(*) as count from port_goods where place = '" . $row['place'] . "' and upgrade = '" . $row['good'] . "'");
			
					if ($rs->data_seek(0) && $subrow = $rs->fetch_assoc()) {
						if ($subrow['count'] > 0) {
							continue;
						}
					}
				}

				$db->get_db()->autocommit(false);

				if (!($st = $db->get_db()->prepare('update port_goods set amount = ?, last_update = ? where record_id = ? and amount = ?'))) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					echo (__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					return;
				}
				
				$st->bind_param("iiii", $amount, $time, $id, $row['amount']);
				
				if (!$st->execute()) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					echo ("Query execution failed: (" . $st->errno . ") " . $st->error);
					return;
				}

				$db->get_db()->commit();
				$db->get_db()->autocommit(true);
			}

			// Dealers second

			$item_types = array();

			$rs = $db->get_db()->query("select * from item_types");
			
			$rs->data_seek(0);
			while ($row = $rs->fetch_assoc()) {
				$item_types[$row['record_id']] = $row;
			}

			$update_dealers = array();
			$update_dealer_count = 0;

			$rs = $db->get_db()->query("select record_id, stock, item_type, last_update, place from dealer_inventory where last_update < " . ($time - PORT_UPDATE_TIME) . " order by last_update limit " . PORTS_PER_UPDATE);
			
			$rs->data_seek(0);
			while ($row = $rs->fetch_assoc()) {
				$update_dealers[$row['record_id']] = $row;
				$update_dealer_count += 1;
			}

			foreach ($update_dealers as $id => $row) {

				$goods_to_update = ($time - $row['last_update']) * GOODS_PER_UPDATE / PORT_UPDATE_TIME;
				$amount = min($item_types[$row['item_type']]['max_stock'], $row['stock'] + $goods_to_update);	

				$db->get_db()->autocommit(false);

				if (!($st = $db->get_db()->prepare('update dealer_inventory set stock = ?, last_update = ? where record_id = ? and stock = ?'))) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					echo (__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					return;
				}
				
				$st->bind_param("iiii", $amount, $time, $id, $row['stock']);
				
				if (!$st->execute()) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					echo("Query execution failed: (" . $st->errno . ") " . $st->error);
					return;
				}

				$db->get_db()->commit();
				$db->get_db()->autocommit(true);
			}
		}
	}