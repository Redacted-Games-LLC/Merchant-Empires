<?php
/**
 * Loads information about all players in the game.
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

	do { // Dummy loop

		include_once('inc/pagination.php');

	
		$db = isset($db) ? $db : new DB;


		$spacegame['players'] = array();
		$spacegame['player_count'] = 0;

		if ($spacegame['page_number'] > 0) {
			// All players
			$rs = $db->get_db()->query("select record_id, caption, alliance, level, gold_expiration from players where last_turns >= '" . (PAGE_START_TIME - ACTIVE_PLAYER_TIME) . "' order by experience desc, caption limit ". ($spacegame['page_number'] - 1) .", 10");
		}
		else {
			// Online players
			$rs = $db->get_db()->query("select record_id, caption, alliance, level, gold_expiration from players where last_turns >= '" . (PAGE_START_TIME - ONLINE_PLAYER_TIME) . "' order by experience desc, caption");
		}

		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$spacegame['players'][$row['record_id']] = $row;
			$spacegame['player_count']++;
		}



		

	} while (false);



?>