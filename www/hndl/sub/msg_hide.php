<?php
/**
 * Handles hiding and unhiding messages.
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
	include_once('inc/msg_functions.php');

	$return_vars['page'] = 'inbox';
	$return_vars['p'] = (int)$_REQUEST['p'];
	$return_vars['pp'] = (int)$_REQUEST['pp'];

	if (isset($_REQUEST['all'])) {
		$return_vars['all'] = 1;
	}
	
	do { // Dummy Loop

		if (MSG_HIDE_DELETE_TURN_COST > $spacegame['player']['turns']) {
			$return_codes[] = 1018;
			break;
		}
		
		if (!isset($_REQUEST['message']) || !is_numeric($_REQUEST['message']) || $_REQUEST['message'] <= 0) {
			$return_codes[] = 1044;
			break;
		}

		$db = isset($db) ? $db : new DB;

		$turn_cost = MSG_HIDE_DELETE_TURN_COST;
		$player_id = PLAYER_ID;

		// Remove some turns
		if (!($st = $db->get_db()->prepare('update players set turns = turns - ? where record_id = ?'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("ii", $turn_cost, $player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		if ($db->get_db()->affected_rows <= 0) {
			$return_codes[] = 1135;
			break;
		}

		$message = $_REQUEST['message'];

		if (!is_numeric($message)) {
			$message = 0;
			$return_codes[] = 1144;
			break;
		}

		$hidden = 0;

		$rs = $db->get_db()->query("select hidden from message_targets where target = '$player_id' and message = '$message'");
		
		$rs->data_seek(0);
		if ($row = $rs->fetch_assoc()) {
			$hidden = $row['hidden'];
		}
		else {
			$return_codes[] = 1145;
			break;
		}

		if ($hidden <= 0) {
			$hidden = 1;
		}
		else {
			$hidden = 0;
		}

		$read_time = PAGE_START_TIME;
		
		if (!($st = $db->get_db()->prepare("update message_targets set `hidden` = ?, message_targets.read = ? where `target` = ? and message = ?"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("iiii", $hidden, $read_time, $player_id, $message);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		if ($db->get_db()->affected_rows <= 0) {
			$return_codes[] = 1146;
			break;
		}			

		$return_codes[] = 1147;

		unsetMessageWaiting();

	} while (false);