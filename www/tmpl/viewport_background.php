<?php
/**
 * Viewport background
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

	include_once('tmpl/common.php');


	// Show the background of the page based on what place we are over.

	$dir = isset($spacegame['system']) ? $spacegame['system']['direction'] : 'ul';
	
	foreach($spacegame['places'] as $place) {
		switch ($place['place_type']) {
			case 2: // Star
				echo '<div class="viewport_big_star" style="';
				echo 'background: url(res/planets/star.png) no-repeat; background-size: 100% 100%;';
				echo '">&nbsp;</div>';
				break 2;

			case 3: // Earth Planet
				echo '<div class="viewport_big_planetoid" style="';
				echo 'background: url(res/planets/earth_'. $dir .'.png) no-repeat;';
				echo '">&nbsp;</div>';
				
				break 2;

			case 5: // Rocky Planet
				echo '<div class="viewport_big_planetoid" style="';
				echo 'background: url(res/planets/pluto_'. $dir .'.png) no-repeat;';
				echo '">&nbsp;</div>';
			
				break 2;

			case 9: // Warp
				echo '<div class="viewport_big_warp" style="';
				echo 'background: url(res/warp.png) no-repeat; background-size: 100% 100%;';
				echo '">&nbsp;</div>';
				break 2;

			case 12: // Ice Giant Planet
				echo '<div class="viewport_big_planetoid" style="';
				echo 'background: url(res/planets/neptune_'. $dir .'.png) no-repeat;';
				echo '">&nbsp;</div>';
			
				break 2;
		}
	}
?>