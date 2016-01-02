<?php
/**
 * Handler for mine and drone deployment
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

		// $db, $tech, and $good should be set by ship_deploy.php which calls this file.

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


		include_once('inc/systems.php');

		if (!isset($spacegame['system'])) {
			$return_codes[] = 1101;
			break;
		}

		if ($spacegame['system']['protected']) {
			$return_codes[] = 1105;
			break;
		}



		$amount = 1;

		if (isset($_REQUEST['amount']) && is_numeric($_REQUEST['amount']) && $_REQUEST['amount'] > 0 && $_REQUEST['amount'] == floor($_REQUEST['amount'])) {
			$amount = $_REQUEST['amount'];
		}

		if ($amount > $tech['amount']) {
			$amount = $tech['amount'];
		}

		include_once('inc/ordnance.php');

		$owner_id = 0;
		$current_count = 0;
		$player_count = 0;

		foreach ($spacegame['sector']['m']['ordnance'] as $ordnance_id => $ordnance) {

			// Each ordnance type is counted separately.
			if ($ordnance['good'] != $good['record_id']) {
				continue;
			}

			$current_count += $ordnance['amount'];

			if ($spacegame['player']['record_id'] == $ordnance['owner']) {
				// This will only run once but let the loop finish
				// for the total count.

				$owner_id = $ordnance_id;
				$player_count += $ordnance['amount'];
			}
		}

		if ($player_count >= MAX_ORDNANCE_PER_PLAYER) {
			$return_codes[] = 1102;
			break;
		}

		if ($amount > MAX_ORDNANCE_PER_SECTOR - $current_count) {
			$amount = MAX_ORDNANCE_PER_SECTOR - $current_count;
		}

		if ($amount > MAX_ORDNANCE_PER_PLAYER - $player_count) {
			$amount = MAX_ORDNANCE_PER_PLAYER - $player_count;
		}

		if ($amount <= 0) {
			$return_codes[] = 1102;
			break;
		}


		// Remove from cargo

		if (!($st = $db->get_db()->prepare("update player_cargo set amount = amount - ? where record_id = ? and amount = ?"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("iii", $amount, $cargo_id, $tech['amount']);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}

		// Add or update ordnance

		if ($owner_id > 0) {
			// Update ordnance
			$amount += $player_count;

			if (!($st = $db->get_db()->prepare("update ordnance set amount = ? where record_id = ?"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("ii", $amount, $owner_id);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break;
			}						

		}
		else {
			// Add ordnance
			$alliance = $spacegame['player']['alliance'] > 0 ? $spacegame['player']['alliance'] : null;

			if (!($st = $db->get_db()->prepare("insert into ordnance (system, x, y, good, amount, owner, alliance) values (?, ?, ?, ?, ?, ?, ?)"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("iiiiiii", $spacegame['system']['record_id'], $spacegame['player']['x'], $spacegame['player']['y'], $good['record_id'], $amount, $spacegame['player']['record_id'], $alliance);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break;
			}
		}





	} while (false);


?>