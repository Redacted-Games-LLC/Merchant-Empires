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
	include_once('inc/goods.php');
	
	do { // Dummy loop
		
		$db = isset($db) ? $db : new DB;

		$rs = $db->get_db()->query("select * from good_upgrades");
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$spacegame['goods'][$row['good']]['targets'][$row['target']] = $spacegame['goods'][$row['target']];
			$spacegame['goods'][$row['good']]['target_count'] = isset($spacegame['goods'][$row['good']]['target_count']) ? $spacegame['goods'][$row['good']]['target_count'] + 1 : 1;
			$spacegame['goods'][$row['target']]['sources'][$row['good']] = $spacegame['goods'][$row['good']];
			$spacegame['goods'][$row['target']]['source_count'] = isset($spacegame['goods'][$row['target']]['source_count']) ? $spacegame['goods'][$row['target']]['source_count'] + 1 : 1;
		}

	} while (false);


?>