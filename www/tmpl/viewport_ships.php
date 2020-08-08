<?php
/**
 * Viewport ships.
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
	include_once('inc/ships.php');
?>
	<div class="viewport_ships align_center">
			
		<div class="viewport_hostile_ships">
			<div class="viewport_hostile_ships_head align_left">
				Hostile Ships &amp; Ordnance
			</div>
			<?php
				$success = false;

				if (isset($spacegame['sector']['m']['hostile_ordnance_count']) && $spacegame['sector']['m']['hostile_ordnance_count'] > 0) {

					$mines = 0;
					$drones = 0;				

					foreach($spacegame['sector']['m']['hostile_ordnance'] as $ordnance_id => $ordnance) {

						switch ($ordnance['good']) {

							case 33: // Mines
								$mines += $ordnance['amount'];
								$success = true;
								echo '<img class="pointer" onclick="open_attack_force('. $ordnance['record_id'] .');" src="res/hostile_mines.png" width="24" height="24" title="'. ($ordnance['alliance'] > 0 ? 'Alliance: ' . $spacegame['alliances'][$ordnance['alliance']]['caption'] . ' - ' : '') . $ordnance['amount'] .' Mine(s). Click to Attack." />';
								break;

							case 34: // Drones
								$drones += $ordnance['amount'];
								$success = true;

								echo '<img class="pointer" onclick="open_attack_force('. $ordnance['record_id'] .');" src="res/hostile_drones.png" width="24" height="24" title="'. ($ordnance['alliance'] > 0 ? 'Alliance: ' . $spacegame['alliances'][$ordnance['alliance']]['caption'] . ' - ' : '') . $ordnance['amount'] .' Drone(s). Click to Attack." />';
								break;
						}
					}

					echo "<small><br />$drones Drone(s) and $mines Mine(s)<br /></small>";
				}

				foreach ($players as $id => $player) {
					
					if ($id == $spacegame['player']['record_id']) {
						continue;
					}
					else if ($player['alliance'] > 0 && $player['alliance'] == $spacegame['player']['alliance']) {
						continue;
					}
					else if ($player['x'] != $spacegame['player']['x'] || $player['y'] != $spacegame['player']['y']) {
						continue;
					}
					else if ($player['base_id'] > 0) {
						if ($spacegame['player']['base_id'] != $player['base_id']) {
							continue;
						}

						if ($player['base_x'] != $spacegame['player']['base_x'] || $player['base_y'] != $spacegame['player']['base_y']) {
							continue;
						}	
					}					

					echo '<div class="hostile_ship">';

					echo '<div class="ship_level align_right">';
					echo 'L' . $player['level'] . ' ';
					echo $spacegame['races'][$player['race']]['caption'];
					echo '</div>';

					echo '<div class="ship_alliance align_left">';
					if ($player['alliance'] > 0) {
						echo $spacegame['alliances'][$player['alliance']]['caption'];
					}
					else {
						echo '<em>No Alliance</em>';
					}
					echo '</div>';

					echo '<div class="ship_player_name align_center">';

					$weight = '';

					if ($player['gold_expiration'] > PAGE_START_TIME) {
						echo '<img src="res/gold.png" alt="gold" title="Gold Member" height="12" />';
						$weight = '_bold';
					}

					echo '<a href="#" onclick="open_player(' . $id . ');">';
					echo "<span class='normal{$weight}'>";
					echo $player['caption'];
					echo '</span>';
					echo '</a>';

					echo '</div>';

					echo '<div class="ship_name align_center">';
					echo '<img class="bottom" src="res/unknown_ship.png" width="16" height="16" alt="unknown_ship" />';
					echo $player['ship_name'] == '' ? DEFAULT_SHIP_NAME : $player['ship_name'];
					echo '</div>';

					echo '<div class="ship_links align_right" title="Show Attack Popup" onclick="return open_attack(' . $player['record_id'] . ');">';
						echo 'Attack';
					echo '</div>';

					echo '<div class="ship_type align_left">';
					echo $player['attack_rating'] . ':' . compute_dr($player);
					echo ' ' . $spacegame['ships'][$player['ship_type']]['caption'];
					echo '</div>';

					echo '&nbsp;';
					echo '</div>';
					$success = true;
				
				}

				if (!$success) {
					echo '<br class="clear" />';
					echo '<div class="margin">Nothing Detected in Sector</div>';
				}
			?>
			
		</div>
		
		<div class="viewport_safe_ships">
			<div class="viewport_safe_ships_head align_left">
				Allied Ships &amp; Ordnance
			</div>
			<?php
				$success = false;

				if (isset($spacegame['sector']['m']['allied_ordnance_count']) && $spacegame['sector']['m']['allied_ordnance_count'] > 0) {

					$mines = 0;
					$drones = 0;

					foreach($spacegame['sector']['m']['allied_ordnance'] as $ordnance_id => $ordnance) {

						switch ($ordnance['good']) {

							case 33: // Mines
								$mines += $ordnance['amount'];
								$success = true;
								echo '<img src="res/allied_mines.png" width="32" height="32" title="'. $ordnance['amount'] .' Mine(s)" />';
								break;

							case 34: // Drones
								$drones += $ordnance['amount'];
								$success = true;
								echo '<img src="res/allied_drones.png" width="32" height="32" title="'. $ordnance['amount'] .' Drones(s)" />';
								break;
						}
					}

					echo "<small><br />$drones Drone(s) and $mines Mine(s)<br /></small>";
				}

				foreach ($players as $id => $player) {
					
					if ($id == $spacegame['player']['record_id']) {
						continue;
					}
					else if ($player['alliance'] <= 0 || $player['alliance'] != $spacegame['player']['alliance']) {
						continue;
					}
					else if ($player['x'] != $spacegame['player']['x'] || $player['y'] != $spacegame['player']['y']) {
						continue;
					}
					else if ($player['base_id'] > 0) {
						if ($spacegame['player']['base_id'] != $player['base_id']) {
							continue;
						}

						if ($player['base_x'] != $spacegame['player']['base_x'] || $player['base_y'] != $spacegame['player']['base_y']) {
							continue;
						}	
					}

					echo '<div class="allied_ship">';

					echo '<div class="ship_level align_right">';
					echo 'L' . $player['level'] . ' ';
					echo $spacegame['races'][$player['race']]['caption'];
					echo '</div>';

					echo '<div class="ship_alliance align_left">';
					if ($player['alliance'] > 0) {
						echo $spacegame['alliances'][$player['alliance']]['caption'];
					}
					else {
						echo '<em>No Alliance</em>';
					}
					echo '</div>';

					echo '<div class="ship_player_name align_center">';
					
					if ($player['gold_expiration'] > PAGE_START_TIME) {
						echo '<img src="res/gold.png" alt="gold" title="Gold Member" height="12" />';
					}

					echo '<a href="#" onclick="open_player(' . $id . ');">';
					echo $player['caption'];
					echo '</a>';
					
					echo '</div>';

					echo '<div class="ship_name align_center">';
					echo '<img class="bottom" src="res/unknown_ship.png" width="16" height="16" alt="unknown_ship" />';
					echo $player['ship_name'] == '' ? DEFAULT_SHIP_NAME : $player['ship_name'];
					echo '</div>';
					
					echo '<div class="ship_links align_right">';
					echo '&nbsp;';
					echo '</div>';

					echo '<div class="ship_type align_left">';
					echo $player['attack_rating'] . ':' . compute_dr($player);
					echo ' ' . $spacegame['ships'][$player['ship_type']]['caption'];
					echo '</div>';

					echo '&nbsp;';
					echo '</div>';
					$success = true;
				}

				if (!$success) {
					echo '<br class="clear" />';
					echo '<div class="margin">Nothing Detected in Sector</div>';
				}
			?>
		</div>
	</div>