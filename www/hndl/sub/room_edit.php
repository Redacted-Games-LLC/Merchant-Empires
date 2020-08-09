<?php
/**
 * Handles editing a room type already in the database.
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

	$return_page = 'admin';
	$return_vars['page'] = 'build';

	do { // Dummy Loop
		
		if (!isset($_REQUEST['room']) || !isset($spacegame['room_index'][$_REQUEST['room']])) {
			$return_codes[] = 1166;
			break;
		}

		$room = $spacegame['room_types'][$spacegame['room_index'][$_REQUEST['room']]];
		$return_vars['page'] = 'room';
		$return_vars['room'] = $room['safe_caption'];
		
		// Verify all the inputs

		if (!isset($_REQUEST['caption']) || strlen($_REQUEST['caption']) <= 1) {
			$return_codes[] = 1111;
			break;
		}

		if (trim($_REQUEST['caption']) != $_REQUEST['caption']) {
			$return_codes[] = 1111;
			break;
		}

		if (str_replace('  ', ' ', $_REQUEST['caption']) != $_REQUEST['caption']) {
			$return_codes[] = 1111;
			break;
		}

		if (!preg_match('/^[a-zA-Z0-9-_\'" ]{1,24}$/i', $_REQUEST['caption'])) {
			$return_codes[] = 1110;
			break;
		}

		$caption = $_REQUEST['caption'];

		if (!isset($_REQUEST['width']) || !is_numeric($_REQUEST['width']) || $_REQUEST['width'] < 1 || $_REQUEST['width'] > MAX_BASE_ROOM_SIZE) {
			$return_codes[] = 1172;
			break;
		}

		$width = $_REQUEST['width'];

		if (!isset($_REQUEST['height']) || !is_numeric($_REQUEST['height']) || $_REQUEST['height'] < 1 || $_REQUEST['height'] > MAX_BASE_ROOM_SIZE) {
			$return_codes[] = 1172;
			break;
		}

		$height = $_REQUEST['height'];

		if (!isset($_REQUEST['build_limit']) || !is_numeric($_REQUEST['build_limit']) || $_REQUEST['build_limit'] < 0) {
			$return_codes[] = 1173;
			break;
		}

		$build_limit = $_REQUEST['build_limit'];

		if (!isset($_REQUEST['build_time']) || !is_numeric($_REQUEST['build_time']) || $_REQUEST['build_time'] < 0) {
			$return_codes[] = 1173;
			break;
		}

		$build_time = $_REQUEST['build_time'];

		if (!isset($_REQUEST['build_cost']) || !is_numeric($_REQUEST['build_cost']) || $_REQUEST['build_cost'] < 0) {
			$return_codes[] = 1173;
			break;
		}

		$build_cost = $_REQUEST['build_cost'];

		if (!isset($_REQUEST['turn_cost']) || !is_numeric($_REQUEST['turn_cost']) || $_REQUEST['turn_cost'] < 0) {
			$return_codes[] = 1173;
			break;
		}

		$turn_cost = $_REQUEST['turn_cost'];

		if (!isset($_REQUEST['experience']) || !is_numeric($_REQUEST['experience']) || $_REQUEST['experience'] < 0) {
			$return_codes[] = 1173;
			break;
		}

		$experience = $_REQUEST['experience'];

		if (!isset($_REQUEST['armor']) || !is_numeric($_REQUEST['armor']) || $_REQUEST['armor'] < 0) {
			$return_codes[] = 1173;
			break;
		}

		$armor = $_REQUEST['armor'];

		if (!isset($_REQUEST['shield_generators']) || !is_numeric($_REQUEST['shield_generators']) || $_REQUEST['shield_generators'] < 0) {
			$return_codes[] = 1173;
			break;
		}

		$shield_generators = $_REQUEST['shield_generators'];

		if (!isset($_REQUEST['turrets']) || !is_numeric($_REQUEST['turrets']) || $_REQUEST['turrets'] < 0) {
			$return_codes[] = 1173;
			break;
		}

		$turrets = $_REQUEST['turrets'];

		if (!isset($_REQUEST['turret_damage']) || !is_numeric($_REQUEST['turret_damage']) || $_REQUEST['turret_damage'] < 0) {
			$return_codes[] = 1173;
			break;
		}

		$turret_damage = $_REQUEST['turret_damage'];

		if (!isset($_REQUEST['power']) || !is_numeric($_REQUEST['power'])) {
			$_REQUEST['power'] = 0;
		}

		$power = $_REQUEST['power'];

		$can_land = 0;

		if (isset($_REQUEST['can_land'])) {
			$can_land = 1;
		}

		$good = 0;
		$production = 0;

		if (isset($_REQUEST['good']) && $_REQUEST['good'] != '[none]') {

			include_once('inc/goods.php');

			if (!isset($spacegame['good_index'][$_REQUEST['good']])) {
				$return_codes[] = 1042;
				break;
			}

	    	$good = $spacegame['good_index'][$_REQUEST['good']];

	    	if (isset($_REQUEST['production']) && !is_numeric($_REQUEST['production']) || $_REQUEST['production'] < 0) {
				$return_codes[] = 1173;
				break;
			}

			$production = $_REQUEST['production'];

		}
    	
		// Alright we should be ready to rock
		$db = isset($db) ? $db : new DB;
		$st = null;

		if ($good <= 0) {
			if (!($st = $db->get_db()->prepare("update room_types set caption = ?, width = ?, height = ?, hit_points = ?, build_time = ?, build_cost = ?, build_limit = ?, turn_cost = ?, can_land = ?, power = ?, good = null, production = 0, shield_generators = ?, turrets = ?, turret_damage = ?, experience = ? where caption = ?"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("siiiiiidiiiiiis", $caption, $width, $height, $armor, $build_time, $build_cost, $build_limit, $turn_cost, $can_land, $power, $shield_generators, $turrets, $turret_damage, $experience, $room['caption']);
		}
		else {
			if (!($st = $db->get_db()->prepare("update room_types set caption = ?, width = ?, height = ?, hit_points = ?, build_time = ?, build_cost = ?, build_limit = ?, turn_cost = ?, can_land = ?, power = ?, good = ?, production = ?, shield_generators = ?, turrets = ?, turret_damage = ?, experience = ? where caption = ?"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("siiiiiidiiiiiiiis", $caption, $width, $height, $armor, $build_time, $build_cost, $build_limit, $turn_cost, $can_land, $power, $good, $production, $shield_generators, $turrets, $turret_damage, $experience, $room['caption']);
		}
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}
		
		if ($db->get_db()->affected_rows <= 0) {
			$return_codes[] = 1174;
			break;
		}
		
	} while (false);