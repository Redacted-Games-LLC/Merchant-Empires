<?php
/**
 * Loads information about ports and goods to trade.
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
	include_once('inc/game.php');
	include_once('inc/goods.php');
	include_once('inc/systems.php');
	
	function get_port_buy_value($level = 1, $distance = 1, $stock = 15000) {
		return $level * $distance + (($level + 2) * $distance * $stock / PORT_LIMIT);
	}

	function get_port_sell_value($level = 1, $distance = 1, $stock = 15000) {
		return ($level + 4) * $distance - (4 * $distance * $stock / PORT_LIMIT);
	}
	
	do { // Dummy loop

		$place_id = 0;

		if (defined('USE_PLACE_ID')) {
			$place_id = USE_PLACE_ID;
		}
		else {
			if ((!isset($_REQUEST['plid'])) || (!is_numeric($_REQUEST['plid'])))  {
				header('Location: error.php?rc=1034');
				die();
			}

			include_once('inc/places.php');

			if (!isset($spacegame['places'][$_REQUEST['plid']])) {
				header('Location: error.php?rc=1034');
				die();
			}

			if ($spacegame['places'][$_REQUEST['plid']]['type'] != 'Port') {
				header('Location: error.php?rc=1034');
				die();
			}

			$place_id = $_REQUEST['plid'];
		}

		$spacegame['port_goods'] = array();
		$spacegame['port_goods_count'] = 0;

		$spacegame['port_trades'] = array();
		$spacegame['port_trades_count'] = 0;

		$spacegame['port_trades']['sells'] = array();
		$spacegame['port_trades']['sells_count'] = 0;
		$spacegame['port_trades']['sells_index'] = array();

		$spacegame['port_trades']['buys'] = array();
		$spacegame['port_trades']['buys_count'] = 0;

		$spacegame['port_upgrades'] = array();
		$spacegame['port_upgrades_count'] = 0;

		$spacegame['port_upgrades']['sells'] = array();
		$spacegame['port_upgrades']['sells_count'] = 0;

		$spacegame['port_upgrades']['buys'] = array();
		$spacegame['port_upgrades']['buys_count'] = 0;
		
		$db = isset($db) ? $db : new DB;

		$rs = $db->get_db()->query("select * from port_goods where place = '". $place_id ."' order by upgrade, distance desc, abs(amount) desc, record_id");
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			
			$good = $spacegame['goods'][$row['good']];
			$row['details'] = $good;

			$spacegame['port_goods_count'] += 1;

			if ($row['supply'] <= 0) {
				// Port wants to buy

				$row['final_price'] = $good['level'] + get_port_buy_value($good['level'], $row['distance'], PORT_LIMIT - $row['amount']);
				$row['final_price'] *= INFLATION_MULTIPLIER;
				$row['final_price'] *= $spacegame['tax_multiplier'];
				$row['final_price'] = ceil($row['final_price']);

				$spacegame['port_goods'][$row['record_id']] = $row;

				if ($row['upgrade'] > 0) {
					// Buy for an upgrade goal
					
					if (isset($spacegame['port_trades']['sells_index'][$row['upgrade']])) {
						// Display in buy category

						$spacegame['port_trades']['buys'][$row['record_id']] = $row;
						$spacegame['port_trades']['buys_count'] += 1;
						$spacegame['port_trades_count'] += 1;
					}
					else {
						// Display in upgrade category 

						$spacegame['port_upgrades']['buys'][$row['record_id']] = $row;
						$spacegame['port_upgrades']['buys_count'] += 1;
						$spacegame['port_upgrades_count'] += 1;
					}

					$spacegame['port_upgrades']['targets'][$row['upgrade']][] = $row['record_id'];

					if (isset($spacegame['port_upgrades']['targets_count'][$row['upgrade']])) {
						$spacegame['port_upgrades']['targets_count'][$row['upgrade']]++;
					}
					else {
						$spacegame['port_upgrades']['targets_count'][$row['upgrade']] = 1;
					}					
				}
				else {
					// Buy for a trade demand
					$spacegame['port_trades']['buys'][$row['record_id']] = $row;
					$spacegame['port_trades']['buys_count'] += 1;
					$spacegame['port_trades_count'] += 1;
				}
			}
			else if ($row['supply'] > 0) {
				// Port wants to sell

				$row['final_price'] = $good['level'] + get_port_sell_value($good['level'], $row['distance'], $row['amount']);
				$row['final_price'] *= INFLATION_MULTIPLIER;
				$row['final_price'] *= $spacegame['tax_multiplier'];
				$row['final_price'] = ceil($row['final_price']);

				$spacegame['port_goods'][$row['record_id']] = $row;

				if ($row['upgrade'] > 0) {
					// Sell for an upgrade goal
					$spacegame['port_upgrades']['sells'][$row['record_id']] = $row;
					$spacegame['port_upgrades']['sells_count'] += 1;
					$spacegame['port_upgrades_count'] += 1;
				}
				else {
					// Sell for a trade demand
					$spacegame['port_trades']['sells'][$row['record_id']] = $row;
					$spacegame['port_trades']['sells_count'] += 1;
					$spacegame['port_trades']['sells_index'][$row['good']] = $row['amount'];
					$spacegame['port_trades_count'] += 1;
				}
			}
		}
	} while (false);