<?php
/**
 * Loads information about the base research known by the game
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

		$spacegame['research_items'] = array();
		$spacegame['research_item_count'] = 0;
		$spacegame['research_index'] = array();


		$db = isset($db) ? $db : new DB;

		$rs = $db->get_db()->query("select * from research_items order by caption, record_id");
		$rs->data_seek(0);
			
		while ($row = $rs->fetch_assoc()) {
			$row['safe_caption'] = strtolower(str_replace(' ', '_', $row['caption']));
			$spacegame['research_index'][$row['safe_caption']] = $row['record_id'];

			$spacegame['research_items'][$row['record_id']] = $row;
			$spacegame['research_items'][$row['record_id']]['upgrades'] = array();
			$spacegame['research_items'][$row['record_id']]['upgrade_count'] = 0;
			$spacegame['research_items'][$row['record_id']]['requirements'] = array();
			$spacegame['research_items'][$row['record_id']]['requirement_count'] = 0;

			$spacegame['research_count']++;
		}


		foreach ($spacegame['research_items'] as $record_id => $room) {
			$rs = $db->get_db()->query("select * from research_requirements where goal = '". $record_id ."'");
			$rs->data_seek(0);
				
			while ($row = $rs->fetch_assoc()) {
				if ($row['research'] > 0) {
					$spacegame['research_items'][$row['reseach']]['upgrades'][$row['record_id']] = $row;
					$spacegame['research_items'][$row['research']]['upgrade_count']++;
				}
				
				$spacegame['research_items'][$record_id]['requirements'][$row['record_id']] = $row;
				$spacegame['research_items'][$record_id]['requirement_count']++;
			}

		}

		
	} while (false);

?>