<?php
/**
 * Viewport stations.
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
?>
	
	<div id="sector_stations" class="align_center">
		<?php
			foreach ($spacegame['places'] as $id => $place) {
				switch ($place['type']) {
					case 'Port':
						if ($spacegame['player']['ship_type'] > 0) {
							echo '<div class="sector_station trade_port" onclick="return open_port(' . $id . ')" title="' . $place['caption'] . '">&nbsp;</div>';
						}
						break;

					case 'Tech Dealer':
						echo '<div class="sector_station tech_dealer" onclick="return open_dealer('. $id .')" title="'. $place['caption'] .'">&nbsp;</div>';
						break;

					case 'Ship Dealer':
						echo '<div class="sector_station ship_dealer" onclick="return open_dealer('. $id .')" title="'. $place['caption'] .'">&nbsp;</div>';
						break;

					case 'Solar Collector':
						if ($spacegame['player']['ship_type'] > 0) {
							echo '<div class="sector_station solar_collector" onclick="return open_dealer('. $id .')" title="'. $place['caption'] .'">&nbsp;</div>';
						}
						break;

					case 'Warp':
						echo '<div class="sector_station warp" onclick="location.href=\'handler.php?task=warp&plid='. $id .'&form_id='. $_SESSION['form_id'] .'\'" title="'. $place['caption'] .'">&nbsp;</div>';
						break;

					case 'Base':
						echo '<div class="sector_station base" onclick="return open_base('. $id .')" title="'. $place['caption'] .'">&nbsp;</div>';
						break;

					default:
						break;
				}
			}
		?>
	</div>