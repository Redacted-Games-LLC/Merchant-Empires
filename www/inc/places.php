<?php
/**
 * Loads up place information
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

	do { // Dummy loop
		
		$spacegame['places'] = array();
		$spacegame['place_count'] = 0;

		$db = isset($db) ? $db : new DB;

		$x = $spacegame['player']['x'];
		$y = $spacegame['player']['y'];

		$rs = $db->get_db()->query("select places.record_id as id, places.type as place_type, places.caption as caption, place_types.caption as type, places.system, x, y from places, place_types where x >= '{$x}' - 1 and y >= '{$y}' - 1 and x <= '{$x}' + 1 and y <= '{$y}' + 1 and places.type = place_types.record_id");
	
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$dx = $row['x'] - $x;
			$dy = $row['y'] - $y;

			$spacegame['sector'][get_dir($dx, $dy)]['places'][] = $row['place_type'];

			if ($x == $row['x'] && $y == $row['y']) {
				$spacegame['places'][$row['id']] = $row;
				$spacegame['place_count']++;
			}
		}
	} while (false);