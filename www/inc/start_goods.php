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
	
	//Warning: may be uncluded by non game docs
	//include_once('inc/common.php');
	
	do { // Dummy loop

		$db = isset($db) ? $db : new DB;
		
		$spacegame['start_goods'] = array();
		$spacegame['start_good_count'] = 0;

		$rs = $db->get_db()->query("select * from start_goods order by supply, place_type, percent desc");

		if (!$rs || !$rs->data_seek(0)) {
			$return_codes[] = 1058;
			error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}

		while ($row = $rs->fetch_assoc()) {
			$spacegame['start_goods'][$row['place_type']][$row['supply'] > 0 ? 'supply' : 'demand'][$row['good']] = $row['percent'];
			$spacegame['start_good_count']++;
		}		
	} while (false);