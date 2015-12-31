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

	include_once('inc/common.php');

	do { // Dummy loop
		
		$spacegame['ships'] = array();
		$spacegame['ships_count'] = 0;
		$spacegame['ships_index'] = array();

		$db = isset($db) ? $db : new DB;

		$rs = $db->get_db()->query("select * from ships order by race, rank, caption");

		$rs->data_seek(0);

		while ($row = $rs->fetch_assoc()) {
			$spacegame['ships'][$row['record_id']] = $row;
			$spacegame['ships_index'][$row['caption']] = $row['record_id'];
			$spacegame['ships_count']++;
		}

	} while (false);



?>