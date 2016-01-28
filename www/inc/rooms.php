<?php
/**
 * Loads information about the base rooms known by the game
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

	include_once('inc/game.php');
	
	do { // Dummy loop
		
		$spacegame['room_types'] = array();
		$spacegame['room_type_count'] = 0;
		$spacegame['room_index'] = array();


		$db = isset($db) ? $db : new DB;

		$rs = $db->get_db()->query("select * from room_types order by caption, record_id");

		$rs->data_seek(0);
			
		while ($row = $rs->fetch_assoc()) {
			$row['safe_caption'] = strtolower(str_replace(' ', '_', $row['caption']));
			$spacegame['room_index'][$row['safe_caption']] = $row['record_id'];

			$spacegame['room_types'][$row['record_id']] = $row;
			$spacegame['room_types'][$row['record_id']]['upgrades'] = array();
			$spacegame['room_types'][$row['record_id']]['upgrade_count'] = 0;
			$spacegame['room_types'][$row['record_id']]['requirements'] = array();
			$spacegame['room_types'][$row['record_id']]['requirement_count'] = 0;

			$spacegame['room_type_count']++;
		}


		foreach ($spacegame['room_types'] as $record_id => $room) {
			$rs = $db->get_db()->query("select * from room_requirements where room = '". $record_id ."'");
			$rs->data_seek(0);
				
			while ($row = $rs->fetch_assoc()) {
				if ($row['build'] > 0) {
					$spacegame['room_types'][$row['build']]['upgrades'][$row['record_id']] = $row;
					$spacegame['room_types'][$row['build']]['upgrade_count']++;
				}
				
				$spacegame['room_types'][$record_id]['requirements'][$row['record_id']] = $row;
				$spacegame['room_types'][$record_id]['requirement_count']++;
			}

		}

		
	} while (false);

?>