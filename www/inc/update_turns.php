<?php
/**
 * Updates turns for a player if need be.
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

	include_once('inc/common.php');
	include_once('inc/game.php');

	do { /* Dummy Loop */


		$turn_delta = PAGE_START_TIME - $spacegame['player']['last_turns'];

		if ($turn_delta >= TURN_UPDATE_TIME) {

			$db = isset($db) ? $db : new DB;

			$turn_updates = floor($turn_delta / TURN_UPDATE_TIME);
			$turns_to_add = min(MAX_TURNS - $spacegame['player']['turns'], TURNS_PER_UPDATE * $turn_updates);
			$turn_delta = TURN_UPDATE_TIME * $turn_updates;
			
			if (!($st = $db->get_db()->prepare('update players set turns = turns + ?, last_turns = last_turns + ? where record_id = ?'))) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break;
			}
		
			$player_id = PLAYER_ID;
			$st->bind_param("dii", $turns_to_add, $turn_delta, $player_id);
		
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break;
			}

			$spacegame['player']['turns'] += $turns_to_add;
			$spacegame['player']['last_turns'] += $turn_delta;
		}


		


	} while (false);




?>