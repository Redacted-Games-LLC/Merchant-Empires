<?php
/**
 * Loads information about known weapon solutions.
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
	include_once('inc/weapons.php');

	do { // Dummy loop
		
		$spacegame['solutions'] = array();
		$spacegame['solution_count'] = 0;

		$spacegame['solution_groups'] = array();
		$spacegame['solution_group_count'] = 0;
		
		$db = isset($db) ? $db : new DB;

		$rs = $db->get_db()->query("select * from solutions where player = '". PLAYER_ID ."' and ship = '". $spacegame['player']['ship_type'] ."' order by sequence limit " . WEAPON_SOLUTION_LIMIT);

		$rs->data_seek(0);

		while ($row = $rs->fetch_assoc()) {
			$spacegame['solutions'][$row['record_id']] = $row;
			$spacegame['solution_count']++;

			if (!isset($spacegame['solution_groups'][$row['group']])) {
				$spacegame['solution_groups'][$row['group']] = array();
				$spacegame['solution_group_count']++;
			}

			$spacegame['solution_groups'][$row['group']][] = $row['record_id'];
		}
		
	} while (false);



?>