<?php
/**
 * Handles base construction options.
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
		
		if ($spacegame['player']['base_id'] <= 0) {
			$return_codes[] = 1116;
			break;
		}

		$x = $spacegame['player']['base_x'];
		$y = $spacegame['player']['base_y'];

		// Make sure we are over a control pad or other factory
		$success = false;

		foreach ($spacegame['over_rooms'] as $room) {

			if ($room['finish_time'] >= PAGE_START_TIME) {
				continue;
			}

			if ($room['caption'] == 'Control Pad') {
				$success = true;
				break;
			}
		}

		if (!$success) {
			$return_codes[] = 1117;
			break;
		}

		include_once('inc/rooms.php');


		$time = PAGE_START_TIME;
		$turns = $spacegame['player']['turns'];
		$turn_cost = 1; // TODO: get turn cost from build.

		if ($turn_cost > $turns) {
			$return_codes[] = 1018;
			break;
		}

		$db = isset($db) ? $db : new DB;

		$player_id = PLAYER_ID;

		// Remove some turns from the player
				
		if (!($st = $db->get_db()->prepare('update players set turns = turns - ? where record_id = ?'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("di", $turn_cost, $player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}
		

		// TODO: Check to see if we are at our limit for concurrent builds


		// TODO: Load info and start the build

		


	} while (false);
	
	

?>