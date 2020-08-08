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

	define("HEADER3_BOLD", '<div class="header3 header_bold">');
	define("HEADER5_BOLD", '<div class="header5 header_bold">');
	define("DIV_CLOSE", '</div>');

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
			include_once('inc/ships.php');
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

				$rs = $db->get_db()->query("select * from players where death = 0 and ship_type > 0 and ((base_id <= 0 and x >= $x - ". DOT_DISTANCE ." and x <= $x + ". DOT_DISTANCE ." and y >= $y - ". DOT_DISTANCE ." and y <= $y + ". DOT_DISTANCE .") or (base_id = '". $spacegame['base']['record_id'] ."' and base_x >= $base_x - ". BASE_DISTANCE ." and base_x <= $base_x + ". BASE_DISTANCE ." and base_y >= $base_y - ". BASE_DISTANCE ." and base_y <= $base_y + ". BASE_DISTANCE .")) order by rank desc, level desc, experience desc, caption");
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
					<div>
						<?php 
								$mine_count = 0;
								$mine_cargo = 0;
								$drone_count = 0;
								$drone_cargo = 0;
								$holds_count = isset($spacegame['ship']['holds']) ? $spacegame['ship']['holds'] : 0;
								$cargo_count = 0;

								if (isset($spacegame['cargo'])) {
									foreach ($spacegame['cargo'] as $cargo_id => $cargo_record) {
										if ($cargo_record['good'] == 33) {
											$mine_cargo = $cargo_id;
											$mine_count += $cargo_record['amount'];
										}
										elseif ($cargo_record['good'] == 34) {
											$drone_cargo = $cargo_id;
											$drone_count += $cargo_record['amount'];
										}
									}
									$cargo_count = $spacegame['cargo_volume'];
								}
						?>
						<div id="force_panel">
							<script type="text/javascript"><!--

								<?php echo "draw_force_panel('{$drone_count}','{$drone_cargo}','{$mine_count}','{$mine_cargo}','{$holds_count}','{$cargo_count}','" . $_SESSION['form_id'] . "');" ?>

							// -->
							</script>
						</div>
					</div>	

					<div class="sector_name align_center">
					<?php
						if ($spacegame['player']['ship_type'] > 0) {
							
							if (isset($spacegame['system'])) {
								echo HEADER5_BOLD;
								echo $spacegame['system']['caption'] . ' System';
								echo DIV_CLOSE;

								echo HEADER3_BOLD;
								echo $spacegame['player']['x'] . ', ' . $spacegame['player']['y'];
								echo DIV_CLOSE;
							}
							elseif ($spacegame['player']['base_id'] > 0) {
								echo HEADER5_BOLD;
								echo $base_caption;
								echo DIV_CLOSE;

								echo HEADER3_BOLD;
								echo $spacegame['player']['base_x'] . ', ' . $spacegame['player']['base_y'];
								echo DIV_CLOSE;
							}
							else {
								echo HEADER5_BOLD;
								echo '&nbsp;';
								echo DIV_CLOSE;

								echo HEADER3_BOLD;
								echo '&nbsp;';
								echo DIV_CLOSE;
							}

							if (isset($spacegame['places'])) {
								$success = false;

								foreach ($spacegame['places'] as $place) {
									switch ($place['place_type']) {
										case '2':
											echo HEADER5_BOLD;
											echo 'Star '. $place['caption'] .DIV_CLOSE;
											$success = true;
											break;

										case '9': // Warp
											echo HEADER5_BOLD. $place['caption'] .DIV_CLOSE;
											$success = true;
											break;											

										case '3': // Earth Planet
										case '5': // Rocky Planet
										case '12': // Ice Giant
											echo HEADER5_BOLD;
											echo 'Planetoid '. $place['caption'] .DIV_CLOSE;
											$success = true;
											break;
									}
								}

								if (!$success) {
									echo HEADER5_BOLD;
									echo '&nbsp;</div>';
								}
							}
							else {
								echo HEADER5_BOLD;
								echo '&nbsp;</div>';
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
								?>
								
								<?php include_once('tmpl/viewport_dss.php'); ?>

								<div class="viewport_console align_center">
									<div class="viewport_console_item align_center" onclick="return open_locator()">
										NAV
									</div>
									<div class="viewport_console_item align_center" onclick="return open_ship()">
										SHIP
									</div>
									<div class="viewport_console_item align_center" onclick="return open_alliance()">
										TEAM
									</div>
									<div class="viewport_console_item align_center" onclick="return open_message(0)">
										MSG
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
	if ($spacegame['player']['ship_type'] > 0) {
		if ($spacegame['player']['base_id'] <= 0 && $spacegame['place_count'] > 6) {
			include_once('tmpl/viewport_stations.php');
		}
		?>
		<div class="viewport_split align_center">

			<table width="100%" role="presentation">
				<tr>
					<td width="200" valign="top"><?php include_once('tmpl/ship_sidebar.php'); ?></td>
					<td valign="top"><?php

						if ($spacegame['player']['base_id'] > 0) {
							echo '&nbsp;';
						}
						else {
							if ($spacegame['place_count'] > 0 && $spacegame['place_count'] <= 6) {
								include_once('tmpl/viewport_stations.php');
							}

							include_once('tmpl/viewport_ships.php');
						}

					?>
					</td>
					<td width="200" valign="top"><?php include_once('tmpl/player_sidebar.php'); ?></td>
				</tr>
			</table>			
			<div class="clear">&nbsp;</div>
		</div>
		<?php
	}
	else {
		if ($spacegame['place_count'] > 0 && $spacegame['player']['base_id'] <= 0) {
			include_once('tmpl/viewport_stations.php');
		}
	}

	if (isset($spacegame['base'])) {
		include_once('tmpl/viewport_base.php');	
	}
		
	?>
		<div class="clear">&nbsp;</div>
		<?php if (isset($_REQUEST['dmg'])) { ?>
			<div class="taking_damage align_center">
				WARNING! TAKING DAMAGE!
			</div>
		<?php } ?>
	</div>
<?php	
	include_once('tmpl/html_end.php');
?>