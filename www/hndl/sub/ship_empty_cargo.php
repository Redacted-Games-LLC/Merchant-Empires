<?php
/**
 * Handles jettisoning cargo from a ship
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

	do { // Dummy loop

		$turns = CARGO_DUMP_TURNS;
		
		if ($spacegame['player']['turns'] < $turns) {
			$return_codes[] = 1018;
			break;
		}


		$db = isset($db) ? $db : new DB;
		$player_id = PLAYER_ID;

		$credits = ($spacegame['player']['level'] + 1) * CARGO_DUMP_COST * INFLATION_MULTIPLIER;


		if (!($st = $db->get_db()->prepare('update players set turns = turns - ?, credits = credits - ? where record_id = ? and credits = ?'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("iiii", $turns, $credits, $player_id, $spacegame['player']['credits']);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		if (!($st = $db->get_db()->prepare('update player_cargo set amount = 0 where player = ? and amount != 0'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("i", $player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}
	
		$return_codes[] = 1066;
		break;

	} while (false);


?>