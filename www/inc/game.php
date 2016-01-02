<?php
/**
 * Loads up stuff which players would need almost every page load.
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

	include_once('inc/common.php');

	if (USER_ID <= 0) {
		header('Location: login.php');
		die();
	}
	
	if (PLAYER_ID <= 0) {
		header('Location: select_player.php');
		die();
	}

	$spacegame = array();
	$spacegame['sector']['ul'] = array();
	$spacegame['sector']['u'] = array();
	$spacegame['sector']['ur'] = array();
	$spacegame['sector']['l'] = array();
	$spacegame['sector']['m'] = array();
	$spacegame['sector']['r'] = array();
	$spacegame['sector']['dl'] = array();
	$spacegame['sector']['d'] = array();
	$spacegame['sector']['dr'] = array();

	$spacegame['gold'] = false;

	function get_dir($dx = 0, $dy = 0) {
		if ($dy < 0) {
			if ($dx < 0) {
				return 'dl';
			}
			elseif ($dx > 0) {
				return 'dr';
			}
			else {
				return 'd';
			}
		}
		elseif ($dy > 0) {
			if ($dx < 0) {
				return 'ul';
			}
			elseif ($dx > 0) {
				return 'ur';
			}
			else {
				return 'u';
			}
			
		}
		else {
			if ($dx < 0) {
				return 'l';
			}
			elseif ($dx > 0) {
				return 'r';
			}
			else {
				return 'm';
			}
		}
	}

	

	$spacegame['actions'] = array(
		'buy' => 1,
		'sell' => 2,
		'upgrade' => 3,
		'war_buy' => 4,
		'war_sell' => 5,
		'war_upgrade' => 6,
	);


	function player_log($player_id, $action, $amount1, $target = 0, $amount2 = 0) {

		$time = PAGE_START_TIME;

		global $db;
		$db = isset($db) ? $db : new DB;

		if (!($st = $db->get_db()->prepare('insert into player_log (player, action, target, amount1, amount2, timestamp) values (?,?,?,?,?,?)'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			return false;
		}
	
		$st->bind_param("iiiiii", $player_id, $action, $target, $amount1, $amount2, $time);
	
		if (!$st->execute()) {
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			return false;
		}

		return true;
	}

	function compute_ardr($player, &$ar, &$dr) {
		$ar = 0;
		$ar += $player['level'] * ATTACK_RATING_PER_LEVEL;

		$ar = round(max($ar, 1));

		$dr = 0;
		$dr += $player['armor'] * DEFENSE_RATING_PER_ARMOR;
		$dr += $player['shields'] * DEFENSE_RATING_PER_SHIELD;

		$dr = round(max($dr, 1));
	}



	do { // Dummy loop
		
		$db = isset($db) ? $db : new DB;
		$time = PAGE_START_TIME;

		$spacegame['races'] = array();
		$rs = $db->get_db()->query("select * from races");
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$spacegame['races'][$row['record_id']] = $row;
		}

		$spacegame['player'] = array();
		$rs = $db->get_db()->query("select * from players where record_id = '" . PLAYER_ID . "' limit 1");
		
		$rs->data_seek(0);
		if ($row = $rs->fetch_assoc()) {
			$spacegame['player'] = $row;
		}
		else {
			header('Location: handler.php?task=logout&rc=1014');
		}

		$spacegame['gold'] = $spacegame['player']['gold_expiration'] > PAGE_START_TIME;

		$spacegame['ship'] = array();

		if ($spacegame['player']['ship_type'] <= 0) {
			$spacegame['ship']['id'] = 0;
			$spacegame['ship']['tps'] = 1;
			$spacegame['ship']['caption'] = 'Escape Pod';
			$spacegame['player']['ship_name'] = 'Long Journey';
		}
		else {
			include_once('inc/ships.php');
			$spacegame['ship'] = $spacegame['ships'][$spacegame['player']['ship_type']];
		}


		$turn_delta = PAGE_START_TIME - $spacegame['player']['last_turns'];

		if ($turn_delta >= TURN_UPDATE_TIME) {

			$turn_updates = floor($turn_delta / TURN_UPDATE_TIME);
			$turns_to_add = min(MAX_TURNS - $spacegame['player']['turns'], TURNS_PER_UPDATE * $turn_updates);
			$turn_delta = TURN_UPDATE_TIME * $turn_updates;
			
			if (!($st = $db->get_db()->prepare('update players set turns = turns + ?, last_turns = last_turns + ? where record_id = ?'))) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break;
			}
		
			$player_id = PLAYER_ID;
			$st->bind_param("iii", $turns_to_add, $turn_delta, $player_id);
		
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break;
			}

			$spacegame['player']['turns'] += $turns_to_add;
			$spacegame['player']['last_turns'] += $turn_delta;
		}


		
		if (PAGE_START_TIME - $spacegame['player']['last_alignment'] >= ALIGNMENT_UPDATE_TIME) {

			$player_id = PLAYER_ID;
			$db->get_db()->autocommit(false);

			$rs = $db->get_db()->query("select * from player_log where player = '{$player_id}' and reconciled <= 0");

			if (!$rs) {
				$db->get_db()->rollback();
				$db->get_db()->autocommit(true);
				error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				return;
			}

			if (!$rs->data_seek(0)) {
				$db->get_db()->rollback();
				$db->get_db()->autocommit(true);
				continue;
			}

			$log_items = array();

			while ($row = $rs->fetch_assoc()) {
				$log_items[$row['record_id']] = $row;
			}

			$alignment_adjust = 0;

			$trades = 0;
			$war_trades = 0;
			$upgrades = 0;
			$war_upgrades = 0;
			
			foreach ($log_items as $log_id => $log_row) {

				switch ($log_row['action']) {

					case 1: // Buy
					case 2: // Sell
						$trades += $log_row['amount1'];
						break;

					case 3: // Upgrade
						$upgrades += $log_row['amount1'];
						break;

					case 4: // War buy
					case 5: // War sell
						$war_trades += $log_row['amount1'];
						break;

					case 6: // War upgrade
						$war_upgrades += $log_row['amount1'];
						break;

					default:
						// Do nothing
				}
			}

			$alignment_adjust -= floor($war_upgrades / WAR_UPGRADES_PER_ALIGNMENT_POINT);
			$alignment_adjust -= floor($war_trades / WAR_TRADES_PER_ALIGNMENT_POINT);
			$alignment_adjust += floor($trades / TRADES_PER_ALIGNMENT_POINT);
			$alignment_adjust += floor($upgrades / UPGRADES_PER_ALIGNMENT_POINT);

			if ($alignment_adjust != 0) {
				foreach ($log_items as $log_id => $log_row) {

					if (!($st = $db->get_db()->prepare("update player_log set reconciled = ? where record_id = ?"))) {
						$db->get_db()->rollback();
						$db->get_db()->autocommit(true);
						error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
						$return_codes[] = 1006;
						return;
					}
					
					$st->bind_param("ii", $time, $log_id);
					
					if (!$st->execute()) {
						$db->get_db()->rollback();
						$db->get_db()->autocommit(true);
						$return_codes[] = 1006;
						error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
						return;
					}
				}
					
				
				$new_alignment = max(-500, min(500, $spacegame['player']['alignment'] + $alignment_adjust));

				if (!($st = $db->get_db()->prepare("update players set alignment = ?, last_alignment = ? where record_id = ? and alignment = ?"))) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					return;
				}

				$st->bind_param("iiii", $new_alignment, $time, $player_id, $spacegame['player']['alignment']);
				
				if (!$st->execute()) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					return;
				}
			}

			$db->get_db()->commit();
			$db->get_db()->autocommit(true);

		}




	} while (false);



?>