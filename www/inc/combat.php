<?php
/**
 * Just a dump of combat functions
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
	include_once('inc/msg_functions.php');

	/**
	 * Performs an attack on a player
	 *
	 * @return boolean true if player is dead
	 */
	function players_attack_player($player_id, $hitters = array(), $already_in_a_transaction = false) {

		if (!is_numeric($player_id) || $player_id <= 0) {
			error_log(__FILE__ . '::' . __LINE__ . ' - Player id is not a positive number greater than zero.');
			return false;
		}

		global $spacegame;
		global $db;

		$db = isset($db) ? $db : new DB;

		if (!$already_in_a_transaction) {
			$db->get_db()->autocommit(false);
		}



		$player = array();

		$rs = $db->get_db()->query("select caption, shields, armor, ship_type, ship_name from players where record_id = '$player_id'");
		
		$rs->data_seek(0);
		
		if ($row = $rs->fetch_assoc()) {
			$player = $row;
		}

		if ($player['ship_type'] <= 0) {
			// Player is in a pod, no further attacks necessary.

			if (!$already_in_a_transaction) {
				$db->get_db()->rollback();
				$db->get_db()->autocommit(true);
			}

			return true;
		}

		// Loop through hitters and attack player one by one


		foreach ($hitters as $hitter) {

			$shield_damage = 0;
			$armor_damage = 0;

			// Apply shield damage

			if (isset($hitter['shield_damage'])) {
				if ($player['shields'] > 0) {
					if ($player['shields'] >= $hitter['shield_damage']) {
						$player['shields'] -= $hitter['shield_damage'];
						$shield_damage += $hitter['shield_damage'];
					}
					else {
						$shield_damage += $player['shields'];
						$player['shields'] = 0;
					}
				}
			}

			// Apply general damage

			if (isset($hitter['damage'])) {
				
				// Buffer for extra damage to armor when shields are depleted
				$carryover = $hitter['damage'];

				if ($player['shields'] >= $hitter['damage']) {
					$player['shields'] -= $hitter['damage'];
					$shield_Damage += $hitter['damage'];
					$carryover = 0;
				}
				else {
					$shield_damage += $player['shields'];
					$carryover = $hitter['damage'] - $player['shields'];
					$player['shields'] = 0;
				}

				if ($player['shields'] <= 0) {
					if ($player['armor'] >= $carryover) {
						$player['armor'] -= $carryover;
						$armor_damage += $carryover;
					}
					else {
						$armor_damage += $player['armor'];
						$player['armor'] = 0;
					}
				}
			}

			// Apply armor damage

			if (isset($hitter['armor_damage'])) {
				if ($player['shields'] <= 0) {
					if ($player['armor'] >= $hitter['armor_damage']) {
						$player['armor'] -= $hitter['armor_damage'];
						$armor_damage += $hitter['armor_damage'];
					}
					else {
						$armor_damage += $player['armor'];
						$player['armor'] = 0;
					}
				}
			}

			player_log($hitter['hitter'], $spacegame['actions']['damage'], $shield_damage + $armor_damage, $player_id);
			
			if ($player['armor'] <= 0) {
				// Dead player

				if (!($st = $db->get_db()->prepare('update players set ship_type = null, shields = 0, armor = 0 where record_id = ?'))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					return true;
				}
				
				$st->bind_param('i', $player_id);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					return true;
				}

				if (!$already_in_a_transaction) {
					if (!$db->get_db()->commit()) {
						error_log(__FILE__ . '::' . __LINE__ . " Commit failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);

					}
					$db->get_db()->autocommit(true);
				}

				return true;
			}

			
			// Damage player


			if (!($st = $db->get_db()->prepare('update players set shields = ?, armor = ? where record_id = ?'))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				$db->get_db()->rollback();
				$db->get_db()->autocommit(true);
				break;
			}
			
			$st->bind_param('iii', $player['shields'], $player['armor'], $player_id);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
				$db->get_db()->rollback();
				$db->get_db()->autocommit(true);
				break;
			}
			
		}



		if (!$already_in_a_transaction) {
			$db->get_db()->commit();
			$db->get_db()->autocommit(true);
		}

		return $player['armor'] <= 0;
	}













?>