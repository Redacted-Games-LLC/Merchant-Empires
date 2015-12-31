<?php
/**
 * 
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
	include_once('inc/ranks.php');

	$return_page = 'viewport';

	do { // dummy loop

		$requested_level = $spacegame['player']['level'] + 1;
		
		if (2000 * $requested_level * $requested_level * $requested_level > $spacegame['player']['experience']) {
			$return_codes[] = 1061;
			break;
		}

		$new_rank = $spacegame['player']['rank'];
		
		// In case there are gaps in the db sequence
		for ($i = 1; $i < 10; $i++) {
			if (isset($spacegame['ranks'][$new_rank + $i])) {
				$new_rank += $i;
				break;
			}
		}

		if ($new_rank == $spacegame['player']['rank']) {
			$return_codes[] = 1064;
		}
		else if ($requested_level < $spacegame['ranks'][$new_rank]['level']) {
			$return_codes[] = 1062;
			$new_rank = $spacegame['player']['rank'];
		}
		else if ($spacegame['player']['alignment'] < $spacegame['ranks'][$new_rank]['alignment']) {
			$return_codes[] = 1063;
			$new_rank = $spacegame['player']['rank'];
		}


		$db = isset($db) ? $db : new DB;
		
		if (!($st = $db->get_db()->prepare('update players set level = ?, rank = ? where record_id = ? and level = ? and rank = ?'))) {
			error_log("Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$player_id = PLAYER_ID;
		$st->bind_param("iiiii", $requested_level, $new_rank, $player_id, $spacegame['player']['level'], $spacegame['player']['rank']);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log("Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}
	
		
	} while (false);
	
	
	
?>