<?php
/**
 * Primary viewport and the screen the player will see the most.
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

	include_once('inc/page.php');
	include_once('inc/game.php');

	do { // Dummy loop
		
		$db = isset($db) ? $db : new DB;

		if ($spacegame['player']['base_id'] <= 0) {
			include_once('inc/places.php');
			include_once('inc/systems.php');

			if (isset($_REQUEST['base'])) {
				include_once('inc/base.php');
			}
		}
		else {
			include_once('inc/base.php');
		}
	
		$spacegame['target_dist'] = 1000000;
		$spacegame['target_dir'] = '';
		
		if ($spacegame['player']['ship_type'] > 0) {
			include_once('inc/cargo.php');
			include_once('inc/alliances.php');

			if ($spacegame['player']['base_id'] <= 0) {
				include_once('inc/ordnance.php');

				if ($spacegame['player']['target_type'] > 0) {
					$dx = $spacegame['player']['target_x'] - $spacegame['player']['x'];
					$dy = $spacegame['player']['target_y'] - $spacegame['player']['y'];
					
					$adx = abs($dx);
					$ady = abs($dy);

					$spacegame['target_dist'] = max($adx, $ady);
					$spacegame['target_dir'] = get_dir($dx, $dy);
				}

			}
		}
		else {
		
			// There should never be a situation where a pod is on a base. All pods
			// are ejected into space.

			$rs = $db->get_db()->query("select places.record_id as id, x, y from places, place_types where places.type = place_types.record_id and place_types.caption = 'Ship Dealer'");
		
			$rs->data_seek(0);
			while ($row = $rs->fetch_assoc()) {
				$dx = $row['x'] - $spacegame['player']['x'];
				$dy = $row['y'] - $spacegame['player']['y'];
				
				$adx = abs($dx);
				$ady = abs($dy);
				
				$dist2 = max($adx, $ady);
				
				if ($dist2 < $spacegame['target_dist']) {
					$spacegame['target_dist'] = $dist2;
					$spacegame['target_dir'] = get_dir($dx, $dy);
				}

			}

		} // if ship_type <= 0
		
		
		$spacegame['players']['safe'] = array();
		$spacegame['players']['hostile'] = array();

		$players = get_players($spacegame['player']);




	} while (false);

	function get_players($player) {

		global $spacegame;
		global $db;
		$db = isset($db) ? $db : new DB;

		$rs = null;

		if ($player['base_id'] > 0) {

			$x = $player['base_x'];
			$y = $player['base_y'];

			$rs = $db->get_db()->query("select * from players where death = 0 and ship_type > 0 and base_id = '". $player['base_id'] ."' and base_x >= $x - ". BASE_DISTANCE ." and base_x <= $x + ". BASE_DISTANCE ." and base_y >= $y - ". BASE_DISTANCE ." and base_y <= $y + ". BASE_DISTANCE ." order by rank desc, level desc, experience desc, caption");
		}
		else {

			if (isset($spacegame['base'])) {
				$x = $player['x'];
				$y = $player['y'];
				$base_x = $player['base_x'];
				$base_y = $player['base_y'];

				$rs = $db->get_db()->query("select * from players where death = 0 and ship_type > 0 and (base_id <= 0 and x >= $x - ". DOT_DISTANCE ." and x <= $x + ". DOT_DISTANCE ." and y >= $y - ". DOT_DISTANCE ." and y <= $y + ". DOT_DISTANCE .") or (base_id = '". $spacegame['base']['record_id'] ."' and base_x >= $base_x - ". BASE_DISTANCE ." and base_x <= $base_x + ". BASE_DISTANCE ." and base_y >= $base_y - ". BASE_DISTANCE ." and base_y <= $base_y + ". BASE_DISTANCE .") order by rank desc, level desc, experience desc, caption");
			}
			else {
				$x = $player['x'];
				$y = $player['y'];

				$rs = $db->get_db()->query("select * from players where death = 0 and ship_type > 0 and base_id <= 0 and x >= $x - ". DOT_DISTANCE ." and x <= $x + ". DOT_DISTANCE ." and y >= $y - ". DOT_DISTANCE ." and y <= $y + ". DOT_DISTANCE ." order by rank desc, level desc, experience desc, caption");
			}
		}

		$rs->data_seek(0);

		$players = array();

		while ($row = $rs->fetch_assoc()) {
			$players[$row['record_id']] = $row;
		}

		return $players;
	}	


	
	$tmpl['page_title'] = 'Viewport';

	include_once('tmpl/html_begin.php');
?>

	<div id="viewport_main_spread">
		<?php 
			if ($spacegame['player']['base_id'] <= 0) {
				include_once('tmpl/viewport_background.php');
			}
		?>

		<div id="viewport_header">
			<div id="viewport_header_left">

				<div id="hud_controls">
					<div class="sector_name">
					<?php
						if ($spacegame['player']['ship_type'] > 0) {

							
							if (isset($spacegame['system'])) {
								echo '<div class="header5">';
								echo $spacegame['system']['caption'] . ' System';
								echo '</div>';

								echo '<div class="header3">';
								echo $spacegame['player']['x'] . ', ' . $spacegame['player']['y'];
								echo '</div>';
							}
							elseif ($spacegame['player']['base_id'] > 0) {
								echo '<div class="header5">';
								echo $base_caption;
								echo '</div>';

								echo '<div class="header3">';
								echo $spacegame['player']['base_x'] . ', ' . $spacegame['player']['base_y'];
								echo '</div>';
							}
							else {
								echo '<div class="header5">';
								echo '&nbsp;';
								echo '</div>';

								echo '<div class="header3">';
								echo '&nbsp;';
								echo '</div>';
							}

							

							if (isset($spacegame['places'])) {
								$success = false;

								foreach ($spacegame['places'] as $place) {
									switch ($place['place_type']) {
										case '2':
											echo '<div class="header5">Star '. $place['caption'] .'</div>';
											$success = true;
											break;

										case '3': // Earth Planet
										case '5': // Rocky Planet
										case '12': // Ice Giant
											echo '<div class="header5">Planetoid '. $place['caption'] .'</div>';
											$success = true;
											break;
									}
								}

								if (!$success) {
									echo '<div class="header5">&nbsp;</div>';
								}
							}
							else {
								echo '<div class="header5">&nbsp;</div>';
							}
						}
						else {
							echo '&nbsp;<br />';
							echo 'YOU ARE IN AN ESCAPE POD<br />';
							echo '<br />';
							
							echo 'FOLLOW THE GREEN ARROW<br />';
							echo 'ON THE LEFT TO REACH A<br />';
							echo 'SHIP DEALER<br />';
							echo '<br />';
							
							if ($spacegame['target_dist'] > 0) {
								echo $spacegame['target_dist'] . ' SECTORS REMAINING';
							}
							else {
								echo '<small>NOW CLICK ON THE SHIP DEALER ICON</small>';
							}
						}
					?>
					</div>

					<?php 
						if ($spacegame['player']['ship_type'] > 0) {

							if ($spacegame['player']['base_id'] <= 0) {
								include_once('tmpl/viewport_dss.php');
							
					?>
						
								<div class="viewport_console">
									<div class="viewport_console_item" onclick="return open_locator()">
										NAV
									</div>
									<div class="viewport_console_item" onclick="return open_ship()">
										SHIP
									</div>
									<div class="viewport_console_item" onclick="return open_alliance()">
										TEAM
									</div>
									<div class="viewport_console_item" onclick="return open_message()">
										COM
									</div>
								</div>
					<?php
							} 
						}
					?>

				</div>

				<?php include_once('tmpl/viewport_nav.php'); ?>
			</div>

			<?php include_once('tmpl/viewport_scan.php'); ?>
			<br class="clear" />
		</div>
		


	<?php
		



	if ($spacegame['player']['base_id'] <= 0 && $spacegame['place_count'] > 0) {
	?>

		<div id="sector_stations">
			<?php
				foreach ($spacegame['places'] as $id => $place) {
					switch ($place['type']) {
						case 'Port':
							if ($spacegame['player']['ship_type'] > 0) {
								echo '<div class="sector_station port" onclick="return open_port(' . $id . ');" title="' . $place['caption'] . '">&nbsp;</div>';
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
	
	<?php
	}


	if ($spacegame['player']['ship_type'] > 0) {
	?>
		
		<div class="viewport_split">
			<div class="viewport_split_panel">
				<?php
					include_once('tmpl/ship_sidebar.php');

					if ($spacegame['player']['base_id'] > 0) {
						echo '</div><div class="viewport_split_panel">';
					}
					else {
						include_once('tmpl/viewport_ships.php');
					}

					include_once('tmpl/player_sidebar.php'); 
				?>
				<br class="clear" />
			</div>
			<br class="clear" />
			<?php if (isset($_REQUEST['dmg'])) { ?>
				<div class="taking_damage">
					TAKING DAMAGE
				</div>
			<?php } ?>
		</div>
	<?php
	}



	if (isset($spacegame['base'])) {
		include_once('tmpl/viewport_base.php');	
	}
	
	


		// All this is to make sure the viewport background isn't cut too short
		// in empty sectors.
	?>

		&nbsp;<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
	
	</div>


	
<?php	
	include_once('tmpl/html_end.php');
?>