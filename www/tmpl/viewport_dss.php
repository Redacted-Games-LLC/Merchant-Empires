<?php
/**
 * Viewport deep space scanner
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

<div class="viewport_dss">
	<div class="viewport_dss_background dss_position">
		<svg width="192" height="96">
			<line x1="0" y1="45" x2="192" y2="45" style="stroke:rgb(48,64,64);stroke-width:1;stroke-opacity:0.8;" />
			<line x1="0" y1="49" x2="192" y2="49" style="stroke:rgb(48,64,64);stroke-width:1;stroke-opacity:0.8;" />
			<line x1="94" y1="0" x2="94" y2="96" style="stroke:rgb(48,64,64);stroke-width:1;stroke-opacity:0.8;" />
			<line x1="98" y1="0" x2="98" y2="96" style="stroke:rgb(48,64,64);stroke-width:1;stroke-opacity:0.8;" />

			<?php

				$scanner_players = array();

				foreach ($players as $id => $player) {
					if ($player['base_id'] > 0) {
						continue;
					}

					if ($id == $spacegame['player']['record_id']) {
						// Uncomment to show self, really just debug
						//echo '<image class="o9" x="'. (88 + $player['x'] - $spacegame['player']['x']) .'" y="'. (38 + $spacegame['player']['y'] - $player['y']) .'" width="15" height="15" xlink:href="res/bdot.png" />';
						continue;
					}

					if ($player['x'] - $spacegame['player']['x'] >= -1 &&
						$player['x'] - $spacegame['player']['x'] <= 1 &&
						$player['y'] - $spacegame['player']['y'] >= -1 &&
						$player['y'] - $spacegame['player']['y'] <= 1) {

						$scanner_players[get_dir($player['x'] - $spacegame['player']['x'], $player['y'] - $spacegame['player']['y'])][] = $player;
					}

					$dt = round((DOT_TIME - (PAGE_START_TIME - $player['last_move'])) / (DOT_TIME / 10));

					if ($dt < 1) {
						continue;
					}
					
					if ($dt > 9) {
						$dt = 9;
					}

					if ($player['alliance'] > 0 && $player['alliance'] == $spacegame['player']['alliance']) {
						echo '<image class="o'.$dt.'" x="'. (88 + $player['x'] - $spacegame['player']['x']) .'" y="'. (38 + $spacegame['player']['y'] - $player['y']) .'" width="15" height="15" xlink:href="res/bdot.png" />';
					}
					else {
						echo '<image class="o'.$dt.'" x="'. (88 + $player['x'] - $spacegame['player']['x']) .'" y="'. (38 + $spacegame['player']['y'] - $player['y']) .'" width="15" height="15" xlink:href="res/rdot.png" />';
					}
				}
			
			?>
			<image x="0" y="0" width="192" height="96" xlink:href="res/dss_frame.png" />
		</svg>
	</div>
	&nbsp;
</div>












