<?php
/**
 * Handles adding a weapon to a solution.
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

	include_once('hndl/common.php');
	include_once('inc/game.php');

	$return_page = 'ship';
	$return_vars['page'] = 'weapons';

	do { // Dummy Loop

		// Remove turns before doing work

		if ($spacegame['player']['turns'] < SOLUTION_TURN_COST) {
			$return_codes[] = 1018;
			break;
		}

		$turn_cost = SOLUTION_TURN_COST;
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

		// Quick checks

		if ($spacegame['player']['ship_type'] <= 0) {
			$return_codes[] = 1183;
			break;
		}

		if (!isset($_REQUEST['solution_group']) || !is_numeric($_REQUEST['solution_group']) || $_REQUEST['solution_group'] < 0 || $_REQUEST['solution_group'] > WEAPON_SOLUTION_LIMIT) {
			$return_codes[] = 1184;
			break;
		}

		$solution_group = $_REQUEST['solution_group'];

		if (!isset($_REQUEST['weapon']) || !is_numeric($_REQUEST['weapon']) || $_REQUEST['weapon'] <= 0) {
			$return_codes[] = 1186;
			break;
		}

		$weapon_add = $_REQUEST['weapon'];

		// More intensive checks

		include_once('inc/solutions.php');
		include_once('inc/ships.php');


		if (!isset($spacegame['weapons'][$weapon_add])) {
			$return_codes[] = 1186;
			break;
		}

		if ($spacegame['weapons'][$weapon_add]['race'] > 0 && $spacegame['weapons'][$weapon_add]['race'] != $spacegame['ship']['race']) {
			$return_codes[] = 1188;
			break;
		}

		if ($spacegame['weapons'][$weapon_add]['racks'] > $spacegame['ship']['racks'] - $spacegame['solution_racks']) {
			$return_codes[] = 1187;
			break;
		}

		if ($spacegame['weapons'][$weapon_add]['stations'] > $spacegame['ship']['stations'] - $spacegame['solution_stations']) {
			$return_codes[] = 1187;
			break;
		}

		if ($solution_group <= 0 && $spacegame['solution_group_count'] >= WEAPON_SOLUTION_LIMIT) {
			$return_codes[] = 1184;
			break;
		}

		// Find out if we are carrying the weapon

		include_once('inc/cargo.php');

		$good_id = $spacegame['weapons'][$weapon_add]['good'];

		if (!isset($spacegame['cargo_index'][$good_id]) || $spacegame['cargo'][$spacegame['cargo_index'][$good_id]]['amount'] <= 0) {
			$return_codes[] = 1186;
			break;
		}

		// Remove it from the cargo
		$amount = 1;

		if (!($st = $db->get_db()->prepare("update player_cargo set amount = amount - ? where record_id = ?"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("ii", $amount, $spacegame['cargo_index'][$good_id]);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}

		// Add weapon solution entry

		$time = PAGE_START_TIME;
		$sequence = 1;

		if ($solution_group <= 0) {

			$i = 1;

			for (; $i <= WEAPON_SOLUTION_LIMIT; $i++) {
				if (!isset($spacegame['solution_groups'][$i])) {
					$solution_group = $i;
					break;
				}

				$solution_group = $i + 1;
			}

		}
		else {
			if (!isset($spacegame['solution_groups'][$solution_group])) {
				$return_codes[] = 1189;
				break;
			}

			$i = 0;

			foreach ($spacegame['solution_groups'][$solution_group] as $solution_id) {
				$i++;

				if ($i != $spacegame['solutions'][$solution_id]['sequence']) {
					$sequence = $i;
					break;
				}

				$sequence = $i + 1;
			}
		}

		if (!($st = $db->get_db()->prepare("insert into solutions (`player`, `weapon`, `ship`, `group`, `sequence`, `fire_time`) values (?,?,?,?,?,?)"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("iiiiii", $player_id, $weapon_add, $spacegame['player']['ship_type'], $solution_group, $sequence, $time);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}

		$weapon = $spacegame['weapons'][$weapon_add];
		$ar = $spacegame['solution_damage'] + ($weapon['volley'] * ($weapon['shield_damage'] + $weapon['armor_damage'] + $weapon['general_damage']));
		$ar *= ATTACK_RATING_PER_DAMAGE;
		$ar += $spacegame['player']['level'] * ATTACK_RATING_PER_LEVEL;
		$ar = round(max($ar, 1));

		if (!($st = $db->get_db()->prepare("update players set attack_rating = ? where record_id = ?"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("ii", $ar, $player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}

		$return_codes[] = 1193;

	} while (false);


?>