<?php
/**
 * Loads ordnance information
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
	include_once('inc/places.php');

	do { // Dummy loop

		$spacegame['ordnance'] = array();
		$spacegame['ordnance_count'] = 0;

		// Load forces in surrounding sectors

		$x = $spacegame['player']['x'];
		$y = $spacegame['player']['y'];
		
		$db = isset($db) ? $db : new DB;

		// We are grabbing one extra sector around systems for the navigation
		// panel and may get multiple systems if stars are close to each other.

		$rs = $db->get_db()->query("select * from ordnance where (x - 1) <= {$x} and (x + 1) >= {$x} and (y - 1) <= {$y} and (y + 1) >= {$y}");
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$spacegame['ordnance'][$row['record_id']] = $row;
			$spacegame['ordnance_count']++;
		}

		foreach ($spacegame['ordnance'] as $ordnance_id => $row) {

			for ($dy = -1; $dy <= 1; $dy++) {

				for ($dx = -1; $dx <= 1; $dx++) {

					$dir = get_dir($dx, $dy);

					if (!isset($spacegame['sector'][$dir]['ordnance_count'])) {
						$spacegame['sector'][$dir]['ordnance'] = array();
						$spacegame['sector'][$dir]['ordnance_count'] = 0;
					}

					if ($row['y'] != $y + $dy) {
						continue;
					}

					if ($row['x'] != $x + $dx) {
						continue;
					}

					$spacegame['sector'][$dir]['ordnance'][$ordnance_id] = $row;
					$spacegame['sector'][$dir]['ordnance_count']++;

					if (!isset($spacegame['sector'][$dir]['allied_ordnance_count'])) {
						$spacegame['sector'][$dir]['allied_ordnance'] = array();
						$spacegame['sector'][$dir]['allied_ordnance_count'] = 0;
					}

					if (!isset($spacegame['sector'][$dir]['hostile_ordnance_count'])) {
						$spacegame['sector'][$dir]['hostile_ordnance'] = array();
						$spacegame['sector'][$dir]['hostile_ordnance_count'] = 0;
					}
					
					if ($row['owner'] == $spacegame['player']['record_id'] || ($row['alliance'] > 0 && $row['alliance'] == $spacegame['player']['record_id'])) {
						// Allied force

						$spacegame['sector'][$dir]['allied_ordnance'][$ordnance_id] = $row;
						$spacegame['sector'][$dir]['allied_ordnance_count']++;
					}
					else {
						// Hostile force

						$spacegame['sector'][$dir]['hostile_ordnance'][$ordnance_id] = $row;
						$spacegame['sector'][$dir]['hostile_ordnance_count']++;
					}
				}
			}
		}



	} while (false);

?>