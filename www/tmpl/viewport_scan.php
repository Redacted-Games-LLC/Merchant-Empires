<?php
/**
 * Viewport scanner box and the scan links.
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

<div id="scan_control_outer">
	<?php if ($spacegame['player']['base_id'] <= 0 && $spacegame['player']['ship_type'] > 0) { ?>
		<div id="scan_controls" class="float_right">
			<?php
				for ($dx = -1; $dx <= 1; $dx++) {
					for ($dy = -1; $dy <= 1; $dy++) {
						$dir = get_dir($dx, $dy);

						echo "<div class='ns_square ns_{$dir}' onclick='return open_scan(" . ($spacegame['player']['x'] + $dx) . ',' . ($spacegame['player']['y'] + $dy) . ")'>";

						echo '<div class="ns_sector">';
		
						if ($spacegame['player']['x'] + $dx < 0 || $spacegame['player']['x'] + $dx > 999 || $spacegame['player']['y'] + $dy < 0 || $spacegame['player']['y'] + $dy > 999) {
							echo '<br class="clear" />';
						}
						else {
							echo '<a onclick="return open_scan(' . ($spacegame['player']['x'] + $dx) . ',' . ($spacegame['player']['y'] + $dy) . ');">';
							echo sprintf("%'.03d %'.03d", $spacegame['player']['x'] + $dx, $spacegame['player']['y'] + $dy);
							echo '</a>';
						}
						
						echo '</div>';

						if (isset($scanner_players[$dir])) {
							$enemy = false;
							$ally = false;

							foreach ($scanner_players[$dir] as $player) {
								if ($player['alliance'] == 0 || $player['alliance'] > 0 && $player['alliance'] != $spacegame['player']['alliance']) {
									$enemy = true;
									
									if ($ally) {
										break;
									}
								}
								else {
									$ally = true;

									if ($enemy) {
										break;
									}	
								}
							}

							if ($enemy) {
								echo '<div class="ns_force_left" title="Enemy Player">&nbsp;</div>';
							}

							if ($ally) {
								echo '<div class="ns_force_right" title="Allied Player">&nbsp;</div>';
							}
						}

						if (isset($spacegame['sector'][$dir]['allied_ordnance_count']) && $spacegame['sector'][$dir]['allied_ordnance_count'] > 0) {
							echo '<div class="ns_force_bottom" title="Allied Forces">&nbsp;</div>';
						}

						if (isset($spacegame['sector'][$dir]['hostile_ordnance_count']) && $spacegame['sector'][$dir]['hostile_ordnance_count'] > 0) {
							echo '<div class="ns_force_top" title="Enemy Forces">&nbsp;</div>';
						}
						

						echo "</div>";
					}
				}
			?>
		</div>
	<?php } else { ?>
		<div id="disabled_scan_controls" class="float_right">
			&nbsp;
		</div>
	<?php } ?>
	<div class="move_scan_text">
		&nbsp;<br />S<br /><br />C<br /><br />A<br /><br />N
	</div>
	
</div>


