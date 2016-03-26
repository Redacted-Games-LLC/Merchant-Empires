<?php
/**
 * Handles removing a weapon from a solution.
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
	include_once('inc/game.php');

	$return_page = 'ship';
	$return_vars['page'] = 'weapons';

	do { // Dummy Loop
		
		// Remove turns before doing work

		if ($spacegame['player']['turns'] < SOLUTION_TURN_COST) {
			$return_codes[] = 1018;
			break;
		}

		$turn_cost = SOLUTION_TURN_COST;
		$player_id = PLAYER_ID;

		$db = isset($db) ? $db : new DB;

		if (!($st = $db->get_db()->prepare("update players set turns = turns - ? where record_id = ?"))) {
			error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("ii", $turn_cost, $player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}

		if (!isset($_REQUEST['solution_id']) || !is_numeric($_REQUEST['solution_id']) || $_REQUEST['solution_id'] <= 0) {
			$return_codes[] = 1189;
			break;
		}

		$solution_id = $_REQUEST['solution_id'];

		include_once('inc/solutions.php');

		if (!isset($spacegame['solutions'][$solution_id])) {
			$return_codes[] = 1189;
			break;
		}

		include_once('inc/cargo.php');
		include_once('inc/ships.php');

		$holds_available = $spacegame['ship']['holds'];

		if (isset($spacegame['cargo_volume'])) {
			$holds_available -= $spacegame['cargo_volume'];
		}

		if ($holds_available < 1) {
			$return_codes[] = 1032;
			break;
		}

		if (!($st = $db->get_db()->prepare("delete from solutions where record_id = ?"))) {
			error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("i", $solution_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}

		$solution = $spacegame['solutions'][$solution_id];
		$weapon = $spacegame['weapons'][$solution['weapon']];
		$player_id = PLAYER_ID;
		$amount = 1;
		
		if (isset($spacegame['cargo_index'][$weapon['good']])) {
			// Update

			$cargo_id = $spacegame['cargo_index'][$weapon['good']];
			
			if (!($st = $db->get_db()->prepare("update player_cargo set amount = amount + ? where record_id = ?"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("ii", $amount, $cargo_id);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
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
			
			$st->bind_param("iii", $player_id, $weapon['good'], $amount);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break;
			}

			$cargo_id = $db->last_insert_id('player_cargo');
		}


	} while (false);


?>