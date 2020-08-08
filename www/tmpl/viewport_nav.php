<?php
/**
 * Viewport movement box and the nav links.
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

<div id="nav_control_outer">
	<div class="float_right move_scan_text align_center">
		&nbsp;<br />M<br /><br />O<br /><br />V<br /><br />E
	</div>

	<div id="nav_controls">
		<?php

			for ($dx = -1; $dx <= 1; $dx++) {
				for ($dy = -1; $dy <= 1; $dy++) {
					$dir = get_dir($dx, $dy);

					$nav_link = 'handler.php?task=move';
					$nav_link .= '&amp;x=' . ($spacegame['player']['x'] + $dx);
					$nav_link .= '&amp;y=' . ($spacegame['player']['y'] + $dy);
					$nav_link .= '&amp;form_id='. $_SESSION['form_id'];

					echo "<div class='ns_square ns_{$dir}' onclick='location.href = ". '"' . $nav_link . '"' . "' title='Move Here'>";
					
					echo '<div class="ns_sector align_center">';
		
					if ($spacegame['player']['x'] + $dx < 0 || $spacegame['player']['x'] + $dx > 999 || $spacegame['player']['y'] + $dy < 0 || $spacegame['player']['y'] + $dy > 999) {
						echo '<br class="clear" />';
					}
					else {
						echo '<a onclick="return lock_validation();" href="' . $nav_link . '"';
						echo ' title="Move Here">';
						echo sprintf("%'.03d %'.03d", $spacegame['player']['x'] + $dx, $spacegame['player']['y'] + $dy);
						echo '</a>';
					}

					echo '</div>';
					
					if ($spacegame['player']['ship_type'] > 0) {

						if (isset($spacegame['sector'][$dir]['places'])) {
							foreach ($spacegame['sector'][$dir]['places'] as $place_type) {
								switch ($place_type) {
									case '1': // Ship Dealer
									case '11': // Tech Dealer
										echo '<div class="ns_location" title="Location">&nbsp;</div>';
										break;

									case '2': // Star
										echo '<div class="ns_middle ns_solar" title="Star">&nbsp;</div>';
										break;

									case '3': // Earth Planet
									case '5': // Rocky Planet
									case '12': // Ice Giant
										echo '<div class="ns_middle ns_planetoid" title="Planetoid">&nbsp;</div>';
										break;

									case '9': // Warp
										echo '<div class="ns_warp" title="Warp Detected">&nbsp;</div>';
										break;
								}
							}
						}

						if (isset($spacegame['sector'][$dir]['system'])) {
							if ($spacegame['sector'][$dir]['system']['protected']) {
								echo '<div class="ns_government" title="Government Protected Sector">&nbsp;</div>';
							}
							else {
								echo '<div class="ns_system" title="Solar System">&nbsp;</div>';
							}
						}
					}
				
					if (isset($spacegame['target_dir']) && $spacegame['target_dir'] == $dir) {
						echo "<div class='ns_arrow ns_arrow_{$dir}'>&nbsp;</div>";
					}

					if($spacegame['player']['base_id'] > 0) {
						echo "<div class='takeoff align_center'>Takeoff</div>";
					}
				
					echo '</div>';
				}
			}
		?>
	</div>
</div>