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

	include_once('inc/common.php');
	include_once('inc/game.php');
	
	do { // Dummy loop
		
		$spacegame['room_types'] = array();
		$spacegame['room_type_count'] = 0;
		$spacegame['room_index'] = array();


		$db = isset($db) ? $db : new DB;

		if (defined('MINIMUM_ROOM_INFO')) {

			$rs = $db->get_db()->query("select * from room_types order by caption, record_id");

			$rs->data_seek(0);
				
			while ($row = $rs->fetch_assoc()) {
				$row['safe_caption'] = strtolower(str_replace(' ', '_', $row['caption']));
				$spacegame['room_index'][$row['safe_caption']] = $row['record_id'];
				$spacegame['room_type_count']++;
			}

		}
		else {
			$rs = $db->get_db()->query("select * from room_types order by caption, record_id");
			$rs->data_seek(0);
				
			while ($row = $rs->fetch_assoc()) {
				$row['safe_caption'] = strtolower(str_replace(' ', '_', $row['caption']));
				$spacegame['room_index'][$row['safe_caption']] = $row['record_id'];

				$spacegame['room_types'][$row['record_id']] = $row;
				$spacegame['room_type_count']++;
			}

			$requirements = array();

			foreach ($spacegame['room_types'] as $room_id => $room) {
				
				$requirements[$room_id]['goods_needed'] = array();
				$requirements[$room_id]['goods_count'] = 0;
				$requirements[$room_id]['researches_needed'] = array();
				$requirements[$room_id]['researches_count'] = 0;
				$requirements[$room_id]['builds_needed'] = array();
				$requirements[$room_id]['builds_count'] = 0;

				if (!isset($requirements[$room_id]['upgrades_count'])) {
					$requirements[$room_id]['upgrades'] = array();
					$requirements[$room_id]['upgrade_count'] = 0;
				}

				$rs = $db->get_db()->query("select * from room_requirements where room = '". $room_id ."'");
				$rs->data_seek(0);
				
				while ($row = $rs->fetch_assoc()) {
					if ($row['good'] > 0) {
						$requirements[$room_id]['goods_needed'][] = $row['good'];
						$requirements[$room_id]['goods_count']++;
					}
			
					if ($row['research'] > 0) {
						$requirements[$room_id]['researches_needed'][] = $row['research'];
						$requirements[$room_id]['researches_count']++;
					}

					if ($row['build'] > 0) {
						if (!isset($requirements[$row['build']]['upgrades_count'])) {
							$requirements[$row['build']]['upgrades'] = array();
							$requirements[$row['build']]['upgrades_count'] = 0;
						}

						$requirements[$row['build']]['upgrades'][] = $room_id;
						$requirements[$row['build']]['upgrades_count']++;

						$requirements[$room_id]['builds_needed'][] = $row['build'];
						$requirements[$room_id]['builds_count']++;
					}
				}
			}

			foreach ($requirements as $room_id => $requirement_list) {
				$spacegame['room_types'][$room_id] = array_merge($spacegame['room_types'][$room_id], $requirements[$room_id]);
			}

		}

		
	} while (false);

?>