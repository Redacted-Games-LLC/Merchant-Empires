<?php
/**
 * Loads gold key information
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
		
		$spacegame['gold_keys'] = array();
		$spacegame['gold_key_count'] = 0;
		
		$db = isset($db) ? $db : new DB;

		$rs = $db->get_db()->query("select * from gold_keys where user = '". USER_ID ."' and used <= 0 order by record_id");
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$spacegame['gold_keys'][$row['record_id']] = $row;
			$spacegame['gold_key_count']++;
		}

	} while (false);



?>