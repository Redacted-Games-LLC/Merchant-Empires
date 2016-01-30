<?php
/**
 * Handles adding a room requirement to the database.
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

		$good = null;
		$amount = 0;
		$research = null;
		$build = null;
	
		include_once('inc/goods.php');

		if (isset($_REQUEST['good']) && $_REQUEST['good'] != '[none]') {
			
			if (!isset($spacegame['good_index'][$_REQUEST['good']])) {
				$return_codes[] = 1042;
				break;
			}
			
			if (!isset($_REQUEST['amount']) || !is_numeric($_REQUEST['amount']) || $_REQUEST['amount'] <= 0) {
				$return_codes[] = 1027;
				break;
			}
			
			$good = $spacegame['good_index'][$_REQUEST['good']];
			$amount = $_REQUEST['amount'];
		}

		include_once('inc/research.php');

		if (isset($_REQUEST['research']) && $_REQUEST['research'] != '[none]') {
			if (!isset($spacegame['research_index'][$_REQUEST['research']])) {
				$return_codes[] = 1067;
				break;
			}
			else {
				$research = $spacegame['research_index'][$_REQUEST['research']];
			}
		}

		if (isset($_REQUEST['build']) && $_REQUEST['build'] != '[none]') {
			if (!isset($spacegame['room_index'][$_REQUEST['build']])) {
				$return_codes[] = 1066;
				break;
			}
			else {
				$build = $spacegame['room_index'][$_REQUEST['build']];
			}
		}

		if (is_null($build) && is_null($research) && is_null($good)) {
			$return_codes[] = 1174;
			break;
		}

		// Alright, lets add the room requirement

		$db = isset($db) ? $db : new DB;
		$st = null;

		if (!($st = $db->get_db()->prepare("insert into room_requirements (room, build, research, good, amount) values (?,?,?,?,?)"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("iiiii", $room['record_id'], $build, $research, $good, $amount);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}
		
		$room_id = $db->last_insert_id('room_requirements');

		if ($room_id <= 0) {
			$return_codes[] = 1170;
			break;
		}

		$return_codes[] = 1175;

		
	} while (false);


?>