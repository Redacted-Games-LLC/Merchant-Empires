<?php
/**
 * Handles marking unread messages as read.
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
		
		if (!isset($_REQUEST['message']) || !is_numeric($_REQUEST['message']) || $_REQUEST['message'] < 0) {
			$return_codes[] = 1144;
			break;
		}

		$db = isset($db) ? $db : new DB;
		
		$player_id = PLAYER_ID;

		$message = $_REQUEST['message'];
		$read_time = PAGE_START_TIME;

		if (!is_numeric($message)) {
			$message = 0;
			$return_codes[] = 1144;
			break;
		}

		if ($message <= 0) {

			// All Messages

			if (!($st = $db->get_db()->prepare("update message_targets set `read` = ? where `target` = ?"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("ii", $read_time, $player_id);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
				break;
			}

			if ($db->get_db()->affected_rows <= 0) {
				$return_codes[] = 1214;
				break;
			}			

			$return_codes[] = 1213;
			
		}
		else {

			// Specific message

			if (!($st = $db->get_db()->prepare("update message_targets set `read` = ? where `target` = ? and message = ?"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("iii", $read_time, $player_id, $message);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
				break;
			}

			if ($db->get_db()->affected_rows <= 0) {
				$return_codes[] = 1214;
				break;
			}			

			$return_codes[] = 1213;
			
		}

		// Remove some turns and remove the message waiting flag.
		$turn_cost = MSG_HIDE_DELETE_TURN_COST;

		if (!($st = $db->get_db()->prepare('update players set turns = turns - ?, unread_messages = 0, messages_read = ? where record_id = ?'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("iii", $turn_cost, $read_time, $player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		if ($db->get_db()->affected_rows <= 0) {
			$return_codes[] = 1135;
			break;
		}

	} while (false);