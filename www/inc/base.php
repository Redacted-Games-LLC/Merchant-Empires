<?php
/**
 * Loads information about a base a player is on or over.
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

		$db = isset($db) ? $db : new DB;

		$base_caption = '';

		if ($spacegame['player']['base_id'] > 0) {
		
			// Landed on a base

			$rs = $db->get_db()->query("select * from bases where record_id = '". $spacegame['player']['base_id'] ."'");
			
			$rs->data_seek(0);
			if ($row = $rs->fetch_assoc()) {
				$spacegame['base'] = $row;
				$spacegame['over_rooms'] = array();
				$spacegame['over_room_count'] = 0;
				$spacegame['rooms'] = array();				
				$spacegame['room_count'] = 0;

				$rs = $db->get_db()->query("select base_rooms.*, room_types.width, room_types.height, room_types.caption from base_rooms, room_types where base = '". $row['record_id'] ."' and base_rooms.room = room_types.record_id");
				$rs->data_seek(0);
			
				while ($row = $rs->fetch_assoc()) {
					$spacegame['rooms'][$row['record_id']] = $row;
					$spacegame['room_count']++;

					if ($spacegame['player']['base_x'] < $row['x']) {
						continue;
					}

					if ($spacegame['player']['base_y'] < $row['y']) {
						continue;
					}

					if ($spacegame['player']['base_x'] >= $row['x'] + $row['width']) {
						continue;
					}

					if ($spacegame['player']['base_y'] >= $row['y'] + $row['height']) {
						continue;
					}

					$spacegame['over_rooms'][$row['record_id']] = $row;
					$spacegame['over_room_count']++;

				}
			}

			$rs = $db->get_db()->query("select caption from places where record_id = '". $spacegame['base']['place'] ."'");
			
			$rs->data_seek(0);
			if ($row = $rs->fetch_assoc()) {
				$base_caption = $row['caption'];
			}

		}
		else if (isset($_REQUEST['plid']) && is_numeric($_REQUEST['plid']) && $_REQUEST['plid'] > 0) {
			
			// Viewing a base



			$rs = $db->get_db()->query("select caption, x, y from places where record_id = '". $_REQUEST['plid'] ."'");
			
			$rs->data_seek(0);
			if ($row = $rs->fetch_assoc()) {
			
				if ($row['x'] != $spacegame['player']['x'] || $row['y'] != $spacegame['player']['y']) {
					$return_codes[] = 1114;
					break;
				}

				$base_caption = $row['caption'];
			}


			// Load base information

			$rs = $db->get_db()->query("select * from bases where place = '". $_REQUEST['plid'] ."'");
			
			$rs->data_seek(0);
			if ($row = $rs->fetch_assoc()) {
				$spacegame['base'] = $row;
				
				$spacegame['over_rooms'] = array();
				$spacegame['over_room_count'] = 0;
				$spacegame['rooms'] = array();
				$spacegame['room_count'] = 0;

				$rs = $db->get_db()->query("select base_rooms.*, room_types.width, room_types.height, room_types.caption from base_rooms, room_types where base = '". $row['record_id'] ."' and base_rooms.room = room_types.record_id");
				$rs->data_seek(0);
			
				while ($row = $rs->fetch_assoc()) {
					$spacegame['rooms'][$row['record_id']] = $row;
					$spacegame['room_count']++;

					if ($row['x'] % 2 == 0) {
						if ($spacegame['player']['base_x'] < $row['x']) {
							continue;
						}
					}
					else {
						if ($spacegame['player']['base_x'] < $row['x'] - 1) {
							continue;
						}
					}

					if ($row['x'] % 2 == 0) {
						if ($spacegame['player']['base_y'] < $row['y']) {
							continue;
						}
					}
					else {
						if ($spacegame['player']['base_y'] < $row['y'] - 1) {
							continue;
						}
					}

					if ($spacegame['player']['base_x'] >= $row['x'] + $row['width']) {
						continue;
					}

					if ($spacegame['player']['base_y'] >= $row['y'] + $row['height']) {
						continue;
					}

					$spacegame['over_rooms'][$row['record_id']] = $row;
					$spacegame['over_room_count']++;
				}
			}

		}
		else {
			// Nothing to do here.
			break;
		}
		
	} while (false);

?>