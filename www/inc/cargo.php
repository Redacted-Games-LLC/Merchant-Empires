<?php
/**
 * Loads cargo information
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

	include_once('inc/game.php');
	include_once('inc/goods.php');

	do { // Dummy loop
		
		$spacegame['cargo'] = array();
		$spacegame['cargo_count'] = 0;
		$spacegame['cargo_volume'] = 0;
		$spacegame['cargo_index'] = array();

		$spacegame['tech'] = array();
		$spacegame['tech_count'] = 0;

		$db = isset($db) ? $db : new DB;

		if (defined('ALL_CARGO') && ALL_CARGO) {
			$rs = $db->get_db()->query("select * from player_cargo where player = '". PLAYER_ID ."' order by record_id");
		}
		else {
			$rs = $db->get_db()->query("select * from player_cargo where player = '". PLAYER_ID ."' and amount > 0 order by record_id");
		}
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$spacegame['cargo'][$row['record_id']] = $row;
			$spacegame['cargo_count']++;
			$spacegame['cargo_volume'] += $row['amount'];
			$spacegame['cargo_index'][$row['good']] = $row['record_id'];

			if ($spacegame['goods'][$row['good']]['tech'] > 0) {
				$spacegame['tech'][$row['record_id']] = $row;
				$spacegame['tech_count']++;
			}
		}

	} while (false);



?>