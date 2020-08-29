<?php
/**
 * Loads information about alliances in the game.
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
		
		$spacegame['alliances'] = array();
		$spacegame['alliances_count'] = 0;
		
		$db = isset($db) ? $db : new DB;

		$rs = $db->get_db()->query("select * from alliances order by caption");
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$spacegame['alliances'][$row['record_id']] = $row;
			$spacegame['alliances_count']++;
		}

		$spacegame['alliance_members'] = array();

		$rs = $db->get_db()->query("select record_id, alliance from players where alliance > 0 order by alliance");
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$spacegame['alliance_members'][$row['alliance']][] = $row['record_id'];
		}
	} while (false);