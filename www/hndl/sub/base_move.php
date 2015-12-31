<?php
/**
 * Handles moving a player from one sector to another, on a base.
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
	
	$return_page = 'viewport';

	do { // dummy loop

		// This sub page should have common stuff loaded by its parent.


		$rx = 0;
		$ry = 0;
	
		if (isset($_REQUEST['x']) && is_numeric($_REQUEST['x'])) {
			$rx = $_REQUEST['x'];
		
			if ($spacegame['player']['base_id'] > 0) {
				if ($rx < 0 || $rx > 100) {
					$return_codes[] = 1016;
					break;
				}
			}
			else {
				if ($rx < 0 || $rx > 50) {
					$return_codes[] = 1016;
					break;
				}
			}

		}

		if (isset($_REQUEST['y']) && is_numeric($_REQUEST['y'])) {
			$ry = $_REQUEST['y'];
			
			if ($spacegame['player']['base_id'] > 0) {
				if ($ry < 0 || $ry > 100) {
					$return_codes[] = 1016;
					break;
				}
			}
			else {
				if ($ry < 0 || $ry > 50) {
					$return_codes[] = 1016;
					break;
				}
			}
		}
		
		$x = $spacegame['player']['base_x'];
		$y = $spacegame['player']['base_y'];

		$time = PAGE_START_TIME;
		$turns = $spacegame['player']['turns'];
		$turn_cost = $spacegame['ship']['tps'];
		
		if ($spacegame['player']['base_id'] <= 0) {
			$turn_cost *= BASE_HOVER_TURN_MULTIPLIER;
			$rx *= 2;
			$ry *= 2;
		}

		if ($turn_cost > $turns) {
			$return_codes[] = 1018;
			break;
		}

		$dx = $rx - $x;
		$dy = $ry - $y;

		if ($spacegame['player']['base_id'] <= 0) {
			
			if ($dx < -2 || $dx > 2) {
				$return_codes[] = 1016;
				break;
			}
			
			if ($dy < -2 || $dy > 2) {
				$return_codes[] = 1016;
				break;
			}
		}
		else {

			if ($dx < -1 || $dx > 1) {
				$return_codes[] = 1016;
				break;
			}
			
			if ($dy < -1 || $dy > 1) {
				$return_codes[] = 1016;
				break;
			}
		}

		if ($dx == 0 && $dy == 0) {
			// Don't do anything but allow the "refresh"
			break;
		}
		
		$db = isset($db) ? $db : new DB;

		$player_id = PLAYER_ID;
		$alliance_id = $spacegame['player']['alliance'];


		// Remove some turns and move the player
				
		if (!($st = $db->get_db()->prepare('update players set base_x = base_x + ?, base_y = base_y + ?, turns = turns - ?, last_move = ? where record_id = ? and base_x = ? and base_y = ?'))) {
			error_log("Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("iidiiii", $dx, $dy, $turn_cost, $time, $player_id, $x, $y);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log("Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}
	

				
	} while (false);
	
	

?>