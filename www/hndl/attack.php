<?php
/**
 * Handles attacking another player.
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

		// Remove turns before doing work

		if ($spacegame['player']['turns'] < ATTACK_TURN_COST) {
			$return_codes[] = 1018;
			break;
		}

		$turn_cost = ATTACK_TURN_COST;
		$player_id = PLAYER_ID;

		$db = isset($db) ? $db : new DB;

		if (!($st = $db->get_db()->prepare("update players set turns = turns - ? where record_id = ?"))) {
			error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("ii", $turn_cost, $player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}
		
		// Some checks to make sure this is ok
	
		// Is player at a level which allows combat?
		if ($spacegame['player']['level'] < MINIMUM_KILLABLE_LEVEL) {
			$return_codes[] = 1194;
			break;
		}

		$player_id = 0;

		if (isset($_REQUEST['player_id']) && is_numeric($_REQUEST['player_id']) && $_REQUEST['player_id'] > 0) {
			$player_id = $_REQUEST['player_id'];
		}

		$force_id = 0;

		if (isset($_REQUEST['force_id']) && is_numeric($_REQUEST['force_id']) && $_REQUEST['force_id'] > 0) {
			$force_id = $_REQUEST['force_id'];
		}

		// Gotta be either player or force (todo: or base)
		if ($player_id <= 0 && $force_id <= 0) {
			$return_codes[] = 1197;
			break;
		}

		// But can't be both of player or force (todo: or base)
		if ($player_id > 0 && $force_id > 0) {
			$return_codes[] = 1198;
			break;
		}

		// Check which solution group we are firing
		if (!isset($_REQUEST['solution_group']) || !is_numeric($_REQUEST['solution_group']) || $_REQUEST['solution_group'] <= 0) {
			$return_codes[] = 1189;
			break;
		}

		$solution_group = $_REQUEST['solution_group'];

		include_once('inc/ships.php');
		include_once('inc/ranks.php');

		// Set up the news message. This is a sloppy way of doing things.		
		$message = '';

		$message .= $spacegame['races'][$spacegame['player']['race']]['caption'];
		$message .= ' ' . $spacegame['ranks'][$spacegame['player']['rank']]['caption'];
		$message .= ' ' . $spacegame['player']['caption'];
		$message .= ' in a';
		$message .= ' ' . $spacegame['races'][$spacegame['ships'][$spacegame['player']['ship_type']]['race']]['caption'];
		$message .= ' ' . $spacegame['ships'][$spacegame['player']['ship_type']]['caption'];

		if (strlen($spacegame['player']['ship_name']) <= 0) {
			$message .= ' "' . DEFAULT_SHIP_NAME . '"';
		}
		else {
			$message .= ' "' . $spacegame['player']['ship_name'] . '"';
		}

		$message .= ' has attacked';

		$force = array();
		$ship = array();
		$player = array();
		$player_shields = 0;
		$player_armor = 0;
		$hitters = array();


		$db = isset($db) ? $db : new DB;

		if ($force_id > 0) {
			// We are attacking forces in a sector. Check who they belong to
			// and make that player the "victim"

			if ($spacegame['player']['base_id'] > 0) {
				$return_codes[] = 1200;
				break;
			}

			$message .= ' forces belonging to';

			$rs = $db->get_db()->query("select * from ordnance where record_id = '" . $force_id . "' and x = '" . $spacegame['player']['x'] . "' and y = '" . $spacegame['player']['y'] . "'");

			$rs->data_seek(0);

			if (!($force = $rs->fetch_assoc())) {
				$return_codes[] = 1200;
				break;
			}

			$player_id = $force['owner'];
		}


		// If we were attacking forces, we should now have the owner. Otherwise, we now have the player.
		// Let's load their info.

		$rs = $db->get_db()->query("select * from players where record_id = '" . $player_id . "'");	

		$rs->data_seek(0);

		if (!($player = $rs->fetch_assoc())) {
			$return_codes[] = 1200;
			break;
		}

		// Now if we were attacking the player we need to do some more work.

		if ($force_id <= 0) {

			// Make sure we are on the same base or in orbit together

			if ($player['base_id'] != $spacegame['player']['base_id']) {
				$return_codes[] = 1200;
				break;
			}

			if ($player['base_id'] > 0) {
				if ($player['base_x'] != $spacegame['player']['base_x'] || $player['base_y'] != $spacegame['player']['base_y']) {

					// If the target moved within the last second and in an adjacent
					// sector still we will allow the attack.

					if (PAGE_START_TIME - $player['last_move'] > 1) {
						$return_codes[] = 1200;
						break;
					}

					if (abs($spacegame['player']['base_x'] - $player['base_y']) > 1) {
						$return_codes[] = 1200;
						break;
					}

					if (abs($spacegame['player']['base_y'] - $player['base_y']) > 1) {
						$return_codes[] = 1200;
						break;
					}
				}
			}
			else {
				if ($player['x'] != $spacegame['player']['x'] || $player['y'] != $spacegame['player']['y']) {

					// If the target moved within the last second and in an adjacent
					// sector still we will allow the attack.

					if (PAGE_START_TIME - $player['last_move'] > 1) {
						$return_codes[] = 1200;
						break;
					}

					if (abs($spacegame['player']['x'] - $player['y']) > 1) {
						$return_codes[] = 1200;
						break;
					}

					if (abs($spacegame['player']['y'] - $player['y']) > 1) {
						$return_codes[] = 1200;
						break;
					}
				}

				if ($player['ship_type'] <= 0) {
					$return_codes[] = 1195;
					break;
				}

				if ($player['level'] < MINIMUM_KILLABLE_LEVEL) {
					$return_codes[] = 1194;
					break;
				}

				include_once('inc/systems.php');

				if ($player['alignment'] > SAFE_ALIGNMENT_MINIMUM) {
					if (isset($spacegame['system']) && $spacegame['system']['protected']) {
						if ($player['attack_rating'] < SAFE_ATTACK_RATING_LIMIT) {
							$return_codes[] = 1207;
							break;	
						}

						if ($spacegame['player']['attack_rating'] < SAFE_ATTACK_RATING_LIMIT) {
							$return_codes[] = 1208;
							break;	
						}
					}
				}
			}

			$player_shields = $player['shields'];
			$player_armor = $player['armor'];

			$ship = $spacegame['ships'][$player['ship_type']];
		}


		// We have our target info. Now lets load our weapon solutions and see what
		// kind of damage we are going to do.

		include_once('inc/solutions.php');
		
		if (!isset($spacegame['solution_groups'][$solution_group])) {
			$return_codes[] = 1189;
			break;
		}

		include_once('inc/cargo.php');

		$goods_to_reduce = array();
		
		// Fire the weapons

		$time = PAGE_START_TIME;

		$total_damage = 0;
		
		$message .= ' ' . $spacegame['races'][$player['race']]['caption'];
		$message .= ' ' . $spacegame['ranks'][$player['rank']]['caption'];
		$message .= ' ' . $player['caption'];

		if ($force_id <= 0) {
			$message .= ' in a';
			$message .= ' ' . $spacegame['races'][$spacegame['ships'][$player['ship_type']]['race']]['caption'];
			$message .= ' ' . $spacegame['ships'][$player['ship_type']]['caption'];

			if (strlen($player['ship_name']) <= 0) {
				$message .= ' "' . DEFAULT_SHIP_NAME . '"';
			}
			else {
				$message .= ' "' . $player['ship_name'] . '"';
			}

		}

		if ($player['base_id'] > 0) {
			$message .= ' on a base';
		}
		
		$message .= ' in sector ' . $spacegame['player']['x'] . ',' . $spacegame['player']['y'] . ':<br /><br />';

		$fire_count = 0;

		// Loop through weapons in solution and get firing.

		foreach ($spacegame['solution_groups'][$solution_group] as $solution_id) {

			$damage_caused = 0;

			$solution = $spacegame['solutions'][$solution_id];
			$elapsed = $time - $solution['fire_time'];

			if ($elapsed <= ATTACK_FLOOD_DELAY) {
				$return_codes[] = 1196;
				break 2;
			}

			// Record weapon firing no matter what

			if (!($st = $db->get_db()->prepare("update solutions set fire_time = ? where record_id = ?"))) {
				error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("ii", $time, $solution_id);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break;
			}

			$weapon = $spacegame['weapons'][$solution['weapon']];

			$message .= '<span class="weapon_caption">' . $weapon['caption'] . '</span>';

			if ($weapon['ammunition'] > 0) {
				if (!isset($spacegame['cargo_index'][$weapon['ammunition']])) {
					$message .= ' <span class="ammo">*EMPTY*</span><br />';
					continue;
				}

				$cargo = $spacegame['cargo'][$spacegame['cargo_index'][$weapon['ammunition']]];

				if ($cargo['amount'] < $weapon['volley']) {
					$message .= ' <span class="ammo">*EMPTY*</span><br />';
					continue;
				}
				else {
					$spacegame['cargo'][$spacegame['cargo_index'][$weapon['ammunition']]]['amount'] -= $weapon['volley'];

					if (!isset($goods_to_reduce[$weapon['ammunition']])) {
						$goods_to_reduce[$weapon['ammunition']] = 0;
					}

					$goods_to_reduce[$weapon['ammunition']] += $weapon['volley'];
				}
			}


			$recharge = RECHARGE_TIME_PER_DAMAGE * $weapon['volley'] * ($weapon['shield_damage'] + $weapon['general_damage'] + $weapon['armor_damage']);
			$recharge += $spacegame['ship']['recharge'];

			if ($recharge < 1) {
				$recharge = 1;
			}

			if ($elapsed > $recharge) {
				$elapsed = $recharge;
			}

			$shield_damage = $weapon['shield_damage'];
			$general_damage = $weapon['general_damage'];
			$armor_damage = $weapon['armor_damage'];

			$accuracy = $weapon['accuracy'] * $elapsed / $recharge;

			$message .= ' at ' . round($accuracy * 100) . '%';

			if ($weapon['accuracy'] < 1.0) {
				// Projectile with a chance to miss
				
				if (mt_rand(0, 1000) > $accuracy * 1000) {
					$shield_damage = 0;
					$armor_damage = 0;
					$general_damage = 0;
				}
			}
			else {
				// Energy weapon with potency

				$shield_damage *= $accuracy;
				$armor_damage *= $accuracy;
				$general_damage *= $accuracy;
			}

			if ($force_id > 0) {
				$damage_caused = $shield_damage + $armor_damage + $general_damage;
			}
			else {
				if ($player_shields > 0) {
					if ($shield_damage < $player_shields) {
						$player_shields -= $shield_damage;
						$damage_caused += $shield_damage;
					}
					else {
						$shield_damage -= $player_shields;
						$damage_caused += $player_shields;
						$player_shields = 0;
					}
				}

				if ($player_shields > 0) {
					if ($general_damage < $player_shields) {
						$player_shields -= $general_damage;	
						$damage_caused += $general_damage;
					}
					else {
						$general_damage -= $player_shields;
						$damage_caused += $player_shields;
						$player_shields = 0;
					}
				}

				if ($player_shields <= 0) {

					if ($player_armor > 0) {
						if ($general_damage < $player_armor) {
							$player_armor -= $general_damage;
							$damage_caused += $general_damage;	
						}
						else {
							$general_damage -= $player_armor;
							$damage_caused += $player_armor;
							$player_armor = 0;
						}
					}

					if ($player_armor > 0) {
						if ($armor_damage < $player_armor) {
							$player_armor -= $armor_damage;
							$damage_caused += $armor_damage;	
						}
						else {
							$armor_damage -= $player_armor;
							$damage_caused += $player_armor;
							$player_armor = 0;
						}
					}
				}
			}

			$damage_caused = ceil($damage_caused);

			if ($damage_caused <= 0) {
				$message .= ' <span class="miss">*MISS*</span>';
			}
			else {
				$fire_count++;

				if ($force_id > 0) {
					$message .= ' hit 1 ordnance';
				} else {
					$message .= ' causing ' . $damage_caused . ' damage';

					if ($player_armor <= 0) {
						$message .= ' <span class="kill">*KILL*</span>';
					}
				}
			}

			$message .= '<br />';

			$total_damage += $damage_caused;
		}

		if ($fire_count <= 0) {
			$return_codes[] = 1203;
			break;
		}

		// Remove ammo

		foreach ($goods_to_reduce as $good_id => $amount) {

			if (!($st = $db->get_db()->prepare("update player_cargo set amount = amount - ? where player = ? and good = ? and amount >= ?"))) {
				error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("iiii", $amount, $spacegame['player']['record_id'], $good_id, $amount);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break;
			}

			if ($db->get_db()->affected_rows <= 0) {
				$return_codes[] = 1203;
				break 2;
			}
		}



		// Hand out damage

		include_once('inc/combat.php');

		if ($force_id > 0) {

			// Delete forces
			$remaining = $force['amount'] - $fire_count;

			if ($remaining > 0) {
				if (!($st = $db->get_db()->prepare("update ordnance set amount = amount - ? where record_id = ?"))) {
					error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param("ii", $fire_count, $force_id);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					break;
				}
			}
			else {
				if (!($st = $db->get_db()->prepare("delete from ordnance where record_id = ?"))) {
					error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param("i", $force_id);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					break;
				}
			}


			if (!($st = $db->get_db()->prepare("delete from ordnance where amount <= 0"))) {
				error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break;
			}
			
			if ($force['good'] == 33) {
				// Get remaining combat drones in sector.
				$remaining = 0;

				$rs = $db->get_db()->query("select * from ordnance where good = '34' and x = '". $spacegame['player']['x'] ."' and y = '". $spacegame['player']['x'] ."' and (owner = '". $force['owner'] ."' or (alliance > 0 and alliance = '". $force['alliance']."'))");

				$rs->data_seek(0);

				while ($row = $rs->fetch_assoc()) {
					$remaining += ceil($amount * DRONES_ATTACKING_PER_PLAYER);
				}
			}

			if ($remaining > 0) {
				
				// Drones can shoot back. NOTE that even a single drone
				// will shoot back if it is being fired upon.

				$bonus = 1.0;

				if ($spacegame['races'][$spacegame['player']['race']]['caption'] == "Zyck'lirg") {
					$bonus -= ZYCK_ORDNANCE_BONUS;
				}

				$hit_amount = mt_rand(0, ceil($remaining * $bonus));
				$total_damage = $hit_amount * DRONE_ATTACK_DAMAGE;

				if ($total_damage > 0) {
					$hitters[] = array(
						'hitter' => $force['owner'],
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
				$message .= ' drones in sector ' . $spacegame['player']['x'] . ',' . $spacegame['player']['y'];
				$message .= ' causing ' . $total_damage . ' damage.';
				
				if (players_attack_player($spacegame['player']['record_id'], $hitters)) {
					// Player is dead
					$serial = ($spacegame['player']['y'] * 1000) + $spacegame['player']['x'];
					player_log($spacegame['player']['record_id'], $spacegame['actions']['death'], $total_damage, $serial);
				}

				$targets = array($player['record_id'], $spacegame['player']['record_id']);
				send_message($message, $targets, MESSAGE_EXPIRATION, 4);
			}

			$return_codes[] = 1205;
			$return_vars['amt'] = $fire_count;
		}
		else {
			$hitters[] = array(
				'hitter' => $spacegame['player']['record_id'],
				'shield_damage' => $player['shields'] - $player_shields,
				'armor_damage' => $player['armor'] - $player_armor,
			);

			if (players_attack_player($player_id, $hitters)) {
				// Player is dead
				$serial = ($spacegame['player']['y'] * 1000) + $spacegame['player']['x'];
				player_log($player_id, $spacegame['actions']['death'], $total_damage, $serial);
			}

			$targets = array($player['record_id'], $spacegame['player']['record_id']);
			send_message($message, $targets, MESSAGE_EXPIRATION, 4);

			$return_codes[] = 1204;
			$return_vars['amt'] = $total_damage;
		}

		
	} while (false);
	
	
	
?>