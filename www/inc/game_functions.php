<?php
/**
 * Loads up functions commonly used by many game pages (in theory).
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

	include_once ('inc/common.php');

	do { /* Dummy Loop */
	} while (false);


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





?>