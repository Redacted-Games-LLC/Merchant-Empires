<?php
/**
 * Handles moving a player from one sector to another.
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
	
	if (isset($_SESSION['form_id'])) {
		if (!isset($_REQUEST['form_id']) || $_SESSION['form_id'] != $_REQUEST['form_id']) {
			header('Location: viewport.php?rc=1181');
			die();
		}
	}
	
	$return_page = 'viewport';

	do { // dummy loop

		$rx = 0;
		$ry = 0;
	
		if (isset($_REQUEST['x']) && is_numeric($_REQUEST['x'])) {
			$rx = $_REQUEST['x'];
			
			if ($rx < 0 || $rx > 999) {
				$return_codes[] = 1016;
				break;
			}
		}

		if (isset($_REQUEST['y']) && is_numeric($_REQUEST['y'])) {
			$ry = $_REQUEST['y'];
			
			if ($ry < 0 || $ry > 999) {
				$return_codes[] = 1016;
				break;
			}
		}
		
		$x = $spacegame['player']['x'];
		$y = $spacegame['player']['y'];

		if ($spacegame['player']['level'] < 1 && $spacegame['player']['ship_type'] > 0) {
			
			include_once('inc/systems.php');

			if ((@$spacegame['sector_grid'][$x][$y]['protected']) && !(@$spacegame['sector_grid'][$rx][$ry]['protected'])) {
				$return_codes[] = 1070;
				break;
			}
		}

		$target_type = $spacegame['player']['target_type'];

		if ($target_type > 0 && $spacegame['player']['target_x'] == $spacegame['player']['x'] && $spacegame['player']['target_y'] == $spacegame['player']['y']) {
			$target_type = 0;
		}

		include_once('inc/ships.php');

		$time = PAGE_START_TIME;
		$turns = $spacegame['player']['turns'];
		$turn_cost = $spacegame['ship']['tps'];
		
		if ($spacegame['player']['base_id'] > 0) {
			$turn_cost *= BASE_TAKEOFF_TURN_MULTIPLIER;
		}

		if ($turn_cost > $turns) {
			$return_codes[] = 1018;
			break;
		}

		$dx = $rx - $x;
		$dy = $ry - $y;
		
		if ($dx < -1 || $dx > 1) {
			$return_codes[] = 1016;
			break;
		}
		
		if ($dy < -1 || $dy > 1) {
			$return_codes[] = 1016;
			break;
		}
		
		if ($spacegame['player']['base_id'] <= 0 && $dx == 0 && $dy == 0) {
			break;
		}
		
		$db = isset($db) ? $db : new DB;

		$player_id = PLAYER_ID;
		$alliance_id = $spacegame['player']['alliance'];

		$bonus = 1.0;

		if ($spacegame['races'][$spacegame['player']['race']]['caption'] == "Zyck'lirg") {
			$bonus -= ZYCK_ORDNANCE_BONUS;
		}

		include_once('inc/combat.php');
		include_once('inc/msg_functions.php');
				
		define('SKIP_ARTICLES', 1);
		include_once('inc/news.php');
		
		// Mines attack when exiting a sector
		if ($spacegame['player']['base_id'] <= 0 && $spacegame['player']['ship_type'] > 0) {
			
			$mines = array();

			$rs = $db->get_db()->query("select record_id, amount, owner from ordnance where x = '{$x}' and y = '{$y}' and good = '33' and owner <> '{$player_id}' and (alliance is null or alliance <> '{$alliance_id}') order by amount desc, record_id");
			
			$rs->data_seek(0);
			
			while ($row = $rs->fetch_assoc()) {

				if (mt_rand(1, 100) > MINE_HIT_PERCENT) {
					continue;
				}

				$mines[$row['record_id']] = $row;
			}

			$hitters = array();

			$complete_count = 0;
			$complete_damage = 0;

			
			foreach ($mines as $record_id => $row) {

				$hit_amount = mt_rand(0, ceil($row['amount'] * $bonus));
				$total_damage = $hit_amount * MINE_ATTACK_DAMAGE;

				if ($total_damage <= 0) {
					continue;
				}

				$complete_count += $hit_amount;
				$complete_damage += $total_damage;

				$hitters[$row['owner']] = 
					array(
						'hitter' => $row['owner'],
						'damage' => $total_damage
					);

				if (!($st = $db->get_db()->prepare('update ordnance set amount = amount - ? where record_id = ?'))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param('ii', $hit_amount, $record_id);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					break;
				}

				if (!($st = $db->get_db()->prepare('delete from ordnance where record_id = ? and amount <= 0'))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param('i', $record_id);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					break;
				}

				$return_vars['dmg'] = 'true';
				
				// Message mine owners about potential damage to ship
				$message = '';
				$message .= $hit_amount . ' mines in sector ';
				$message .= $spacegame['player']['x'] . ',' . $spacegame['player']['y'];
				$message .= ' caused ' . $total_damage . ' damage to player ';
				$message .= $spacegame['player']['caption'] . '.';
				
				$targets = array($row['owner']);

				send_message($message, $targets, MESSAGE_EXPIRATION, 4);

			}

			if ($complete_damage > 0) {
				// Message player about getting hit.
				$message = '';
				$message .= 'You were hit by ' . $complete_count . ' mines in sector ';
				$message .= $spacegame['player']['x'] . ',' . $spacegame['player']['y'];
				$message .= ' causing ' . $complete_damage . ' damage.';
				
				$targets = array($player_id);
				send_message($message, $targets, MESSAGE_EXPIRATION, 4);
			}


			if (players_attack_player($player_id, $hitters)) {
				// Player is dead
				$serial = ($spacegame['player']['y'] * 1000) + $spacegame['player']['x'];
				player_log($player_id, $spacegame['actions']['death'], $complete_damage, $serial);

				$spacegame['player']['ship_type'] = 0;
				break;
			}
		}




		// Remove some turns and move the player
				
		if (!($st = $db->get_db()->prepare('update players set x = x + ?, y = y + ?, turns = turns - ?, target_type = ?, last_move = ?, base_id = 0, base_x = 50, base_y = 50 where record_id = ? and x = ? and y = ? and turns = ?'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("iidiiiiid", $dx, $dy, $turn_cost, $target_type, $time, $player_id, $x, $y, $turns);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}
	





		// Drones attack when entering a sector

		if ($spacegame['player']['ship_type'] > 0) {
			
			$drones = array();
	
			$rs = $db->get_db()->query("select record_id, amount, owner from ordnance where x = '{$rx}' and y = '{$ry}' and good = '34' and owner <> '{$player_id}' and (alliance is null or alliance <> '{$alliance_id}') order by amount desc, record_id");
		
			$rs->data_seek(0);
			
			while ($row = $rs->fetch_assoc()) {
				$drones[$row['record_id']] = $row;
			}

			$complete_count = 0;
			$complete_damage = 0;

			$hitters = array();

			foreach ($drones as $record_id => $row) {

				if ($row['amount'] == 1) {

					// Send alert and move on

					$message = '';
					$message .= 'Player ' . $spacegame['player']['caption'] . ' ';
					$message .= 'detected but not engaged in sector ';
					$message .= $rx . ',' . $ry;
					$message .= '.';
					
					$targets = array($row['owner']);
					send_message($message, $targets, MESSAGE_EXPIRATION, 4);

					continue;
				}

				$hit_amount = mt_rand(0, ceil($row['amount'] * $bonus));
				$total_damage = $hit_amount * DRONE_ATTACK_DAMAGE;

				if ($total_damage > 0) {
					$hitters[] = array(
						'hitter' => $row['owner'],
						'damage' => $total_damage
					);

					$return_vars['dmg'] = 'true';
				}

				$complete_count += $hit_amount;
				$complete_damage += $total_damage;

				// Message drone owners about potential damage to ship

				$message = '';
				$message .= 'Player ' . $spacegame['player']['caption'] . ' ';
				$message .= 'detected and engaged by ' . $hit_amount;
				$message .= ' drones in sector ' . $rx . ',' . $ry;
				$message .= ' causing ' . $total_damage . ' damage.';
				
				$targets = array($row['owner']);
				send_message($message, $targets, MESSAGE_EXPIRATION, 4);				

			}

			// Message player about getting hit.
			if ($complete_damage > 0) {
				$message = '';
				$message .= 'You were engaged by ' . $complete_count . ' drones in sector ';
				$message .= $rx . ',' . $ry . ' causing ' . $complete_damage . ' damage.';
				
				$targets = array($player_id);
				send_message($message, $targets, MESSAGE_EXPIRATION, 4);
			}

			if (players_attack_player($player_id, $hitters)) {
				$serial = ($ry * 1000) + $rx;
				player_log($player_id, $spacegame['actions']['death'], $complete_damage, $serial);
				break;
			}
		}







				
	} while (false);
	
	
	
?>