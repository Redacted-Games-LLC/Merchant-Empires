<?php
/**
 * Loads up information about the local system. 
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

		$spacegame['systems'] = array();

		$x = $spacegame['player']['x'];
		$y = $spacegame['player']['y'];
		
		$db = isset($db) ? $db : new DB;

		// We are grabbing one extra sector around systems for the navigation
		// panel and may get multiple systems if stars are close to each other.

		$rs = $db->get_db()->query("select * from systems where (x - radius - 1) <= {$x} and (x + radius + 1) >= {$x} and (y - radius - 1) <= {$y} and (y + radius + 1) >= {$y}");
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$spacegame['systems'][$row['record_id']] = $row;
		}

		foreach ($spacegame['systems'] as $system_id => $row) {

			for ($dy = -1; $dy <= 1; $dy++) {
				for ($dx = -1; $dx <= 1; $dx++) {

					if ($x + $dx >= $row['x'] - $row['radius'] && $y + $dy >= $row['y'] - $row['radius']) {
						if ($x + $dx <= $row['x'] + $row['radius'] && $y + $dy <= $row['y'] + $row['radius']) {
							$spacegame['sector_grid'][$x + $dx][$y + $dy] = $row;
							$spacegame['sector'][get_dir($dx, $dy)]['system'] = $row;
						}
					}

				}
			}

		}


		if (isset($spacegame['sector']['m']['system'])) {
			$angle = atan2($spacegame['sector']['m']['system']['x'] - $x, $spacegame['sector']['m']['system']['y'] - $y);
			$angles = array('dl', 'l', 'ul', 'u', 'ur', 'r', 'dr', 'd');

			$spacegame['sector']['m']['system']['direction'] = $angles[3 + ($angle / (M_PI / 4))];
			$spacegame['system'] = $spacegame['sector']['m']['system'];
		}
		

		$spacegame['own_tax_rate'] = $spacegame['races'][$spacegame['player']['race']]['tax_rate'];
		$spacegame['other_tax_rate'] = 0;
		
		if (isset($spacegame['system']) && $spacegame['system']['race'] > 0) {
			if ($spacegame['system']['race'] != $spacegame['player']['race']) {
				$spacegame['other_tax_rate'] = $spacegame['races'][$spacegame['system']['race']]['tax_rate'];
			}
		}

		$spacegame['tax_multiplier'] = 1 + ($spacegame['own_tax_rate'] / 100) + ($spacegame['other_tax_rate'] / 100);

		

	} while (false);

?>