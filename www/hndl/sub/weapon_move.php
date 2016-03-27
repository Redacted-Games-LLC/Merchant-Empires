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

		if (!isset($_REQUEST['direction'])) {
			$return_codes[] = 1190;
			break;
		}

		include_once('inc/solutions.php');

		if (!isset($spacegame['solutions'][$solution_id])) {
			$return_codes[] = 1189;
			break;
		}

		$solution = $spacegame['solutions'][$solution_id];
		$solutions = $spacegame['solution_groups'][$solution['group']];
		$count = count($solutions);

		if ($count == 1) {
			$return_codes[] = 1174;
			break;
		}

		$swap_sequence = 0;

		if ($_REQUEST['direction'] == 'up') {
			if ($solution['sequence'] <= 1) {
				$return_codes[] = 1174;
				break;
			}
			else {
				foreach ($solutions as $record_id) {
					$row = $spacegame['solutions'][$record_id];

					if ($row['sequence'] == $solution['sequence'] - 1) {
						$swap_sequence = $record_id;
					}
				}
			}
		}
		elseif ($_REQUEST['direction'] == 'down') {
			if ($solution['sequence'] >= $count) {
				$return_codes[] = 1174;
				break;
			}
			else {
				foreach ($solutions as $record_id) {
					$row = $spacegame['solutions'][$record_id];

					if ($row['sequence'] == $solution['sequence'] + 1) {
						$swap_sequence = $record_id;
					}
				}

			}
		}
		else {
			$return_codes[] = 1189;
			break;
		}


		if (!($st = $db->get_db()->prepare("update solutions set sequence = -1 where record_id = ?"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("i", $solution['record_id']);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}

		if (!($st = $db->get_db()->prepare("update solutions set sequence = ? where record_id = ?"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("ii", $solution['sequence'], $spacegame['solutions'][$swap_sequence]['record_id']);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}

		if (!($st = $db->get_db()->prepare("update solutions set sequence = ? where record_id = ?"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("ii", $spacegame['solutions'][$swap_sequence]['sequence'], $solution['record_id']);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}

		$return_codes[] = 1191;
	} while (false);


?>