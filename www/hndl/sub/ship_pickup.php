<?php
/**
 * Handles picking up drones, and sometimes mines.
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

	include_once('hndl/common.php');
	
	do { // Dummy Loop

		if ($spacegame['player']['turns'] < DEPLOY_TURN_COST) {
			$return_codes[] = 1018;
			break;
		}

		$db = isset($db) ? $db : new DB;

		// Remove some turns

		$player_id = PLAYER_ID;
		$turn_cost = DEPLOY_TURN_COST;
		
		if (!($st = $db->get_db()->prepare('update players set turns = turns - ? where record_id = ?'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("ii", $turn_cost, $player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}


		if (!isset($_REQUEST['good']) || !is_numeric($_REQUEST['good']) || $_REQUEST['good'] <= 0) {
			$return_codes[] = 1021;
			break;
		}

		include_once('inc/goods.php');

		$good_id = $_REQUEST['good'];

		if ($good_id != $spacegame['goods_index']['Drones']) {
			$return_codes[] = 1103;
			break;
		}

		
		$x = $spacegame['player']['x'];
		$y = $spacegame['player']['y'];


		$record_id = 0;
		$amount = 0;

		$rs = $db->get_db()->query("select record_id, amount from ordnance where owner = '{$player_id}' and x = '{$x}' and y = '{$y}' and good = '{$good_id}'");

		$rs->data_seek(0);
		if ($row = $rs->fetch_assoc()) {
			$record_id = $row['record_id'];
			$amount = $row['amount'];
		}
		
		if ($amount <= 0) {
			$return_codes[] = 1104;
			break;
		}
		
		// Find out if we have room in the cargo
		
		include_once('inc/cargo.php');

		$amount = min($spacegame['ship']['holds'] - $spacegame['cargo_volume'], $amount);

		if ($amount <= 0) {
			$return_codes[] = 1032;
			break;
		}

		// Delete ordnance

		if (!($st = $db->get_db()->prepare("delete from ordnance where record_id = ?"))) {
			error_log("Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("i", $record_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log("Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}

		// Update or insert player cargo

		// NOTE: Technically if they can pickup then they have deployed so they
		// should have a cargo entry. We shouldn't assume though.

		$cargo_id = $spacegame['cargo_index'][$good_id];

		if (isset($spacegame['cargo_index'][$good_id])) {
			// Update
			
			if (!($st = $db->get_db()->prepare("update player_cargo set amount = amount + ? where record_id = ?"))) {
				error_log("Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("ii", $amount, $cargo_id);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log("Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break;
			}


		}
		else {
			// Insert

			if (!($st = $db->get_db()->prepare("insert into player_cargo (player, good, amount) values (?, ?, ?)"))) {
				error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("iii", $player_id, $good_id, $amount);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log("Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break;
			}

			$cargo_id = $db->last_insert_id('player_cargo');
		}

	} while (false);


?>