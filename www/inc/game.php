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
	include_once('inc/game_functions.php');

	if (USER_ID <= 0) {
		header('Location: login.php');
		die();
	}
	
	if (PLAYER_ID <= 0) {
		header('Location: select_player.php');
		die();
	}

	do { // Dummy loop
		
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


		$spacegame['actions'] = array(
			'buy' => 1,
			'sell' => 2,
			'upgrade' => 3,
			'war_buy' => 4,
			'war_sell' => 5,
			'war_upgrade' => 6,
			'death' => 7,
			'damage' => 8,
			'friendly_damage' => 9,
			'war_damage' => 10,
		);

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


		include_once('inc/update_turns.php');
		include_once('inc/update_alignment.php');

	} while (false);



?>