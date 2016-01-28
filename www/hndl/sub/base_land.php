<?php
/**
 * Handles landing a player on a base
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

	include_once('inc/page.php');
	include_once('inc/game.php');
	include_once('inc/ships.php');
	
	$return_page = 'viewport';

	do { // dummy loop

		// This sub page should have common stuff loaded by its parent.
		
		if ($spacegame['player']['base_id'] > 0) {
			$return_codes[] = 1113;
			break;
		}

		if ($spacegame['player']['ship_type'] <= 0) {
			$return_codes[] = 1119;
			break;
		}

		$x = $spacegame['player']['base_x'];
		$y = $spacegame['player']['base_y'];

		// Make sure we are over a landing pad
		$success = false;

		foreach ($spacegame['over_rooms'] as $room) {

			if ($room['finish_time'] >= PAGE_START_TIME) {
				continue;
			}
			
			if ($room['caption'] == 'Control Pad' || $room['caption'] == 'Landing Pad') {
				$success = true;

				$x = $room['x'] + $room['width'] - 3;
				$y = $room['y'] + $room['height'] - 3;

				break;
			}
		}

		if (!$success) {
			$return_codes[] = 1115;
			break;
		}


		$time = PAGE_START_TIME;
		$turns = $spacegame['player']['turns'];
		$turn_cost = $spacegame['ship']['tps'] * BASE_LAND_TURN_MULTIPLIER;

		if ($turn_cost > $turns) {
			$return_codes[] = 1018;
			break;
		}

		$db = isset($db) ? $db : new DB;

		$player_id = PLAYER_ID;

		// Remove some turns and move the player
				
		if (!($st = $db->get_db()->prepare('update players set base_id = ?, base_x = ?, base_y = ?, turns = turns - ?, last_move = ? where record_id = ? and base_id = 0'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("iiidii", $spacegame['base']['record_id'], $x, $y, $turn_cost, $time, $player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}
				
	} while (false);
	
	

?>