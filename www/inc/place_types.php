<?php
/**
 * Loads information about place types
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
		
		$spacegame['place_types'] = array();
		$spacegame['place_types_count'] = 0;
		$spacegame['place_types_index'] = array();
		
		$db = isset($db) ? $db : new DB;

		$rs = $db->get_db()->query("select * from place_types order by caption");

		$rs->data_seek(0);

		while ($row = $rs->fetch_assoc()) {
			$spacegame['place_types'][$row['record_id']] = $row;
			$spacegame['place_types_index'][$row['caption']] = $row['record_id'];
			$spacegame['place_types_count']++;
		}

	} while (false);


?>