<?php
/**
 * Handles port trade
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
	include_once('inc/systems.php');
	include_once('inc/port.php');
	include_once('inc/ships.php');

	if (isset($_SESSION['form_id'])) {
		if (!isset($_REQUEST['form_id']) || $_SESSION['form_id'] != $_REQUEST['form_id']) {
			header('Location: viewport.php?rc=1181');
			die();
		}
	}

	define('ALL_CARGO', true);
	include_once('inc/cargo.php');

	$return_page = 'port';
	$return_vars['plid'] = $place_id;

	do { // Dummy loop

		if ($spacegame['player']['ship_type'] <= 0) {
			$return_codes[] = 1183;
			break;
		}

		if ($spacegame['player']['turns'] < TRADE_TURN_COST) {
			$return_codes[] = 1018;
			break;
		}

		$turn_cost = TRADE_TURN_COST;


		$time = PAGE_START_TIME;
		$player_id = PLAYER_ID;

		if ((!isset($_REQUEST['amount'])) || (!is_numeric($_REQUEST['amount']))) {
			$return_codes[] = 1027;
			break;
		}

		$amount = $_REQUEST['amount'];

		if ((!isset($_REQUEST['item_id'])) || (!is_numeric($_REQUEST['item_id'])) || $_REQUEST['item_id'] <= 0) {
			$return_codes[] = 1021;
			break;
		}

		$item_id = $_REQUEST['item_id'];

		if (!isset($spacegame['port_goods'][$item_id])) {
			$return_codes[] = 1022;
			break;
		}

		$good = $spacegame['port_goods'][$item_id];

		$action_prefix = '';

		if (isset($spacegame['system']) && $spacegame['system']['race'] > 0) {
			if ($spacegame['system']['race'] != $spacegame['player']['race']) {
				$action_prefix = 'war_';	
			}
		}

		if ($good['amount'] < 0) {

			// Port wants to buy -------------------------------------======

			$return_page = 'port';
			$return_vars['plid'] = $place_id;
			
			$action = $spacegame['actions'][$action_prefix . 'sell'];

			if ($amount <= 0) {
				$return_codes[] = 1027;
				break;
			}

			if ($amount + $good['amount'] > 0) {
				$return_codes[] = 1035;
				break;
			}

			if (!isset($spacegame['cargo_index'][$good['good']])) {
				$return_codes[] = 1036;
				break;
			}

			$cargo = $spacegame['cargo'][$spacegame['cargo_index'][$good['good']]];

			if ($cargo['amount'] < $amount) {
				$return_codes[] = 1035;
				break;
			}

			$credits = $good['final_price'] * $amount;
			$experience = floor($good['details']['level'] * $amount / ($good['upgrade'] > 0 ? 4 : 5));
			$good_id = $good['good'];
			
			$db = isset($db) ? $db : new DB;

			$db->get_db()->autocommit(false);

			if (!($st = $db->get_db()->prepare('update port_goods set amount = amount + ?, last_update = ? where record_id = ? and amount <= 0 - ?'))) {
				$db->get_db()->rollback();
				$db->get_db()->autocommit(true);
				error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("iiii", $amount, $time, $item_id, $amount);
			
			if (!$st->execute()) {
				$db->get_db()->rollback();
				$db->get_db()->autocommit(true);
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break;
			}

			if ($good['upgrade'] > 0) {

				// See if there is a supply fed by this upgrade.

				if (isset($spacegame['port_trades']['sells_index'][$good['upgrade']])) {

					$sub_amount = min(PORT_LIMIT, $spacegame['port_trades']['sells_index'][$good['upgrade']] + $amount);

					if (!($st = $db->get_db()->prepare('update port_goods set amount = ?, last_update = ? where place = ? and good = ? and supply = 1 and amount = ?'))) {
						$db->get_db()->rollback();
						$db->get_db()->autocommit(true);
						error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
						$return_codes[] = 1006;
						break;
					}
					
					$st->bind_param("iiiii", $sub_amount, $time, $place_id, $good['upgrade'], $spacegame['port_trades']['sells_index'][$good['upgrade']]);
					
					if (!$st->execute()) {
						$db->get_db()->rollback();
						$db->get_db()->autocommit(true);
						$return_codes[] = 1006;
						error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
						break;
					}
				}
				else {
					$action = $spacegame['actions'][$action_prefix . 'upgrade'];
				}
			}
		
			if (!($st = $db->get_db()->prepare("update players set credits = credits + ?, turns = turns - ?, experience = experience + ? where record_id = ?"))) {
				$db->get_db()->rollback();
				$db->get_db()->autocommit(true);
				error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("iiii", $credits, $turn_cost, $experience, $player_id);
			
			if (!$st->execute()) {
				$db->get_db()->rollback();
				$db->get_db()->autocommit(true);
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break;
			}

			$cargo_id = $spacegame['cargo_index'][$good_id];
			
			// Update

			if (!($st = $db->get_db()->prepare("update player_cargo set sold = sold + ?, amount = amount - ? where record_id = ?"))) {
				$db->get_db()->rollback();
				$db->get_db()->autocommit(true);
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("iii", $amount, $amount, $cargo_id);
			
			if (!$st->execute()) {
				$db->get_db()->rollback();
				$db->get_db()->autocommit(true);
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break;
			}
			
			$db->get_db()->commit();
			$db->get_db()->autocommit(true);

			player_log($player_id, $action, $amount, $place_id);
			$return_codes[] = 1026;
			$return_vars['amt'] = $credits;
		}
		elseif ($good['amount'] > 0) {

			// Port wants to sell -------------------------------------======
			$return_page = 'viewport';

			if ($amount <= 0) {
				$return_codes[] = 1027;
				break;
			}

			if ($amount > $good['amount']) {
				$return_codes[] = 1028;
				break;
			}

			if (!isset($spacegame['ship']['holds'])) {
				$return_codes[] = 1031;
				break;
			}

			$holds_available = $spacegame['ship']['holds'];

			if (isset($spacegame['cargo_volume'])) {
				$holds_available -= $spacegame['cargo_volume'];
			}

			if ($holds_available < $amount) {
				$return_codes[] = 1032;
				break;
			}

			$credits = $good['final_price'] * $amount;
			
			if ($credits > $spacegame['player']['credits']) {
				$return_codes[] = 1025;
				break;
			}

			$good_id = $good['good'];
			$surplus = 0;
			
			$db = isset($db) ? $db : new DB;

			$db->get_db()->autocommit(false);

			if (isset($spacegame['port_upgrades']['targets'][$good_id])) {

				$sub_amount = floor($amount / $spacegame['port_upgrades']['targets_count'][$good_id]);
				$surplus = $amount - ($sub_amount * $spacegame['port_upgrades']['targets_count'][$good_id]);

				if ($sub_amount <= 0) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					$return_codes[] = 1028;
					break;
				}

				if (!($st = $db->get_db()->prepare('update port_goods set amount = amount - ?, last_update = ? where place = ? and upgrade = ? and supply = 0'))) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param("iiii", $sub_amount, $time, $place_id, $good_id);
				
				if (!$st->execute()) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					break;
				}
			}

			$amount -= $surplus;	

			if ($amount <= 0) {
				$db->get_db()->rollback();
				$db->get_db()->autocommit(true);
				$return_codes[] = 1028;
				break;
			}


			if (!($st = $db->get_db()->prepare('update port_goods set amount = amount - ?, last_update = ? where record_id = ? and amount >= ?'))) {
				$db->get_db()->rollback();
				$db->get_db()->autocommit(true);
				error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("iiii", $amount, $time, $item_id, $amount);
			
			if (!$st->execute()) {
				$db->get_db()->rollback();
				$db->get_db()->autocommit(true);
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break;
			}

			if (!($st = $db->get_db()->prepare("update players set credits = credits - ?, turns = turns - ? where record_id = ? and credits >= ?"))) {
				$db->get_db()->rollback();
				$db->get_db()->autocommit(true);
				error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("iiii", $credits, $turn_cost, $player_id, $credits);
			
			if (!$st->execute()) {
				$db->get_db()->rollback();
				$db->get_db()->autocommit(true);
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				break;
			}

			if (isset($spacegame['cargo_index'][$good_id])) {
				// Update

				$cargo_id = $spacegame['cargo_index'][$good_id];
				
				if (!($st = $db->get_db()->prepare("update player_cargo set bought = bought + ?, amount = amount + ? where record_id = ?"))) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param("iii", $amount, $amount, $cargo_id);
				
				if (!$st->execute()) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					break;
				}


			}
			else {
				// Insert

				if (!($st = $db->get_db()->prepare("insert into player_cargo (player, good, amount, bought) values (?, ?, ?, ?)"))) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param("iiii", $player_id, $good_id, $amount, $amount);
				
				if (!$st->execute()) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					break;
				}

				$cargo_id = $db->last_insert_id('player_cargo');
			}

			$db->get_db()->commit();
			$db->get_db()->autocommit(true);

			$action = $spacegame['actions'][$action_prefix . 'buy'];
			player_log($player_id, $action, $amount, $place_id);
			$return_codes[] = 1026;
			$return_vars['amt'] = $credits;
		}
		

	} while (false);


?>