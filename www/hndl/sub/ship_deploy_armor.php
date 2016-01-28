<?php
/**
 * Handles deploying shields and armor.
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

		include_once('inc/ships.php');

		$current_amount = $spacegame['player'][$good['safe_caption']];
		$max_amount = $spacegame['ships'][$spacegame['player']['ship_type']][$good['safe_caption']];

		if ($current_amount >= $max_amount) {
			$return_codes[] = 1100;
			break;
		}

		$change = $max_amount - $current_amount;

		if ($change > $tech['amount']) {
			$change = $tech['amount'];
		}

		// Remove from cargo

		if (!($st = $db->get_db()->prepare("update player_cargo set amount = amount - ? where record_id = ? and amount = ?"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("iii", $change, $cargo_id, $tech['amount']);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}
		
		// Add tech to player

		if (!($st = $db->get_db()->prepare("update players set ". $good['safe_caption']  ." = " . $good['safe_caption'] . " + ?, turns = turns - ? where record_id = ? and " . $good['safe_caption'] . " = ?"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}

		$player_id = PLAYER_ID;
		$turn_cost = DEPLOY_TURN_COST;
		
		$st->bind_param("iiii", $change, $turn_cost, $player_id, $spacegame['player'][$good['safe_caption']]);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}
		


	} while (false);

?>