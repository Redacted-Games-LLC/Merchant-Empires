<?php
/**
 * Loads information about goods in the database.
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
		
		$spacegame['goods'] = array();
		$spacegame['goods_count'] = 0;
		$spacegame['goods_index'] = array();

		$db = isset($db) ? $db : new DB;

		$rs = $db->get_db()->query("select * from goods order by caption");
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$row['safe_caption'] = strtolower(str_replace(' ', '_', $row['caption']));
			$row['source_count'] = 0;
			$row['sources'] = array();
			$row['target_count'] = 0;
			$row['targets'] = array();
			
			$spacegame['goods'][$row['record_id']] = $row;
			$spacegame['goods_index'][$row['caption']] = $row['record_id'];
			$spacegame['goods_count']++;
		}

	} while (false);


?>