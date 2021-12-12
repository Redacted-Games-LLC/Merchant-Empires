<?php
/**
 * Handles dealer transactions for the game.
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
	include_once('inc/dealer.php');
	include_once('inc/ships.php');

	if (isset($_SESSION['form_id'])) {
		if (!isset($_REQUEST['form_id']) || $_SESSION['form_id'] != $_REQUEST['form_id']) {
			header('Location: viewport.php?rc=1181');
			die();
		}
	}

	$return_page = 'viewport';
	$return_vars['plid'] = $place_id;

	do { // Dummy loop

		if ((!isset($_REQUEST['amount'])) || (!is_numeric($_REQUEST['amount'])) || $_REQUEST['amount'] <= 0) {
			$return_codes[] = 1027;
			break;
		}

		$amount = $_REQUEST['amount'];

		if ((!isset($_REQUEST['item_id'])) || (!is_numeric($_REQUEST['item_id']))) {
			$return_codes[] = 1021;
			break;
		}

		$item_id = $_REQUEST['item_id'];

		if (!isset($spacegame['inventory'][$item_id])) {
			$return_codes[] = 1022;
			break;
		}

		$item = $spacegame['inventory'][$item_id];

		if ($item['stock'] <= 0) {
			$return_codes[] = 1023;
			break;
		}

		if ($amount > $item['stock']) {
			$return_codes[] = 1028;
			break;
		}

		if (isset($item['details']['race']) && $item['details']['race'] > 0 && $item['details']['race'] != $spacegame['player']['race']) {
			$return_codes[] = 1024;
			break;
		}

		$player_id = PLAYER_ID;
		$credits = floor($item['final_price'] * $amount);
		$inventory_id = $item['id'];

		switch ($item['item_type']) {
			case 1: // Ship
				
				if ($amount > 1) {
					$return_codes[] = 1028;
					break 2;
				}

				// Ship treatment of starters vis a vis escape pods
				
				if ($spacegame['player']['ship_type'] > 0 || $item['details']['rank'] > 1) {
					if ($credits > $spacegame['player']['credits']) {
						$return_codes[] = 1025;
						break 2;
					}

					$alignment_adjust = 1.0;

					if ($spacegame['player']['alignment'] <= NEG_ALIGN_PER_PERCENT) {
						$alignment_adjust = 1.0 + (floor($spacegame['player']['alignment'] / NEG_ALIGN_PER_PERCENT) / 100);
					}
					else if ($spacegame['player']['alignment'] >= POS_ALIGN_PER_PERCENT) {
						$alignment_adjust = 1.0 - (floor($spacegame['player']['alignment'] / POS_ALIGN_PER_PERCENT) / 100);
					}

					$credits = floor($credits * $alignment_adjust);

				}
				else {
					$credits = 0;
				}

				$ship_type = $item['item'];
				$armor = $item['details']['armor'];
				$shields = $item['details']['shields'];

				define('OVERRIDE_SHIP_TYPE', $ship_type);
				include_once('inc/solutions.php');

				$attack_rating = $spacegame['solution_damage'] * ATTACK_RATING_PER_DAMAGE;
				$attack_rating += $spacegame['player']['level'] * ATTACK_RATING_PER_LEVEL;
				$attack_rating = round(max($attack_rating, 1));
								
				$db = isset($db) ? $db : new DB;

				$db->get_db()->autocommit(false);

				if (!($st = $db->get_db()->prepare('update dealer_inventory set stock = stock - ? where record_id = ? and stock >= ?'))) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break 2;
				}
				
				$st->bind_param("iii", $amount, $inventory_id, $amount);
				
				if (!$st->execute()) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					break 2;
				}

				if (!($st = $db->get_db()->prepare("update player_cargo set amount = 0 where player = ?"))) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break 2;
				}

				$st->bind_param("i", $player_id);
				
				if (!$st->execute()) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					break 2;
				}

				if (!$spacegame['gold']) {
					if (!($st = $db->get_db()->prepare("delete from solutions where player = ?"))) {
						$db->get_db()->rollback();
						$db->get_db()->autocommit(true);
						error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
						$return_codes[] = 1006;
						break 2;
					}
					
					$st->bind_param("i", $player_id);
					
					if (!$st->execute()) {
						$db->get_db()->rollback();
						$db->get_db()->autocommit(true);
						$return_codes[] = 1006;
						error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
						break 2;
					}
				}

				if (!($st = $db->get_db()->prepare("update players set credits = credits - ?, ship_type = ?, ship_name = '', armor = ?, shields = ?, attack_rating = ? where record_id = ? and credits >= ?"))) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break 2;
				}
				
				$st->bind_param("iiiiiii", $credits, $ship_type, $armor, $shields, $attack_rating, $player_id, $credits);
				
				if (!$st->execute()) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					break 2;
				}
			
				$db->get_db()->commit();
				$db->get_db()->autocommit(true);
				$return_codes[] = 1026;
				$return_vars['amt'] = $credits;
				break;

			case 2: // Goods
				define('ALL_CARGO', true);
				include_once('inc/cargo.php');

				$good_id = $item['item'];
				$holds_available = 0;
				
				if (!isset($spacegame['ship']['holds'])) {
					$return_codes[] = 1031;
					break 2;
				}

				$holds_available = $spacegame['ship']['holds'];

				if (isset($spacegame['cargo_volume'])) {
					$holds_available -= $spacegame['cargo_volume'];
				}

				if ($holds_available <= 0) {
					$return_codes[] = 1032;
					break 2;
				}

				if ($amount > $holds_available) {
					$amount = $holds_available;
					$credits = $item['final_price'] * $amount;
				}

				if ($amount <= 0) {
					$return_codes[] = 1032;
					break 2;
				}

				if ($credits > $spacegame['player']['credits']) {
					$return_codes[] = 1025;
					break 2;
				}

				$db = isset($db) ? $db : new DB;

				$db->get_db()->autocommit(false);

				if (!($st = $db->get_db()->prepare('update dealer_inventory set stock = stock - ? where record_id = ? and stock >= ?'))) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break 2;
				}
				
				$st->bind_param("iii", $amount, $inventory_id, $amount);
				
				if (!$st->execute()) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					break 2;
				}
			
				if (!($st = $db->get_db()->prepare("update players set credits = credits - ? where record_id = ? and credits >= ?"))) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break 2;
				}
				
				$st->bind_param("iii", $credits, $player_id, $credits);
				
				if (!$st->execute()) {
					$db->get_db()->rollback();
					$db->get_db()->autocommit(true);
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					break 2;
				}

				if (isset($spacegame['cargo_index'][$good_id])) {
				// Update
					$record_id = $spacegame['cargo_index'][$good_id];
					
					if (!($st = $db->get_db()->prepare("update player_cargo set bought = bought + ?, amount = amount + ? where record_id = ?"))) {
						$db->get_db()->rollback();
						$db->get_db()->autocommit(true);
						error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
						$return_codes[] = 1006;
						break 2;
					}
					
					$st->bind_param("iii", $amount, $amount, $record_id);
					
					if (!$st->execute()) {
						$db->get_db()->rollback();
						$db->get_db()->autocommit(true);
						$return_codes[] = 1006;
						error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
						break 2;
					}
				}
				else {
					// Insert

					$player_id = PLAYER_ID;

					if (!($st = $db->get_db()->prepare("insert into player_cargo (player, good, amount, bought) values (?, ?, ?, ?)"))) {
						$db->get_db()->rollback();
						$db->get_db()->autocommit(true);
						error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
						$return_codes[] = 1006;
						break 2;
					}
					
					$st->bind_param("iiii", $player_id, $good_id, $amount, $amount);
					
					if (!$st->execute()) {
						$db->get_db()->rollback();
						$db->get_db()->autocommit(true);
						$return_codes[] = 1006;
						error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
						break 2;
					}

				}
							
				$db->get_db()->commit();
				$db->get_db()->autocommit(true);
				$return_codes[] = 1026;
				$return_vars['amt'] = $credits;
				break;

			default:
				$return_codes[] = 1020;
				break 2;
		}
		
		$return_page = 'viewport';

	} while (false);