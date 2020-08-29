<?php
/**
 * Handles ignoring and un-ignoring players.
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
	$return_vars['p'] = $_REQUEST['p'];
	$return_vars['pp'] = $_REQUEST['pp'];
	
	do { // Dummy Loop

		if (PLAYER_MESSAGE_IGNORE_COST > $spacegame['player']['turns']) {
			$return_codes[] = 1018;
			break;
		}
		
		if (!isset($_REQUEST['player']) || !validate_playername($_REQUEST['player'])) {
			$return_codes[] = 1011;
			break;
		}

		$db = isset($db) ? $db : new DB;

		$player = 0;

		$rs = $db->get_db()->query("select record_id from players where lower(`caption`) = '". strtolower($_REQUEST['player']) ."'");
		
		$rs->data_seek(0);
		if ($row = $rs->fetch_assoc()) {
			$player = $row['record_id'];
		}
		else {
			$return_codes[] = 1135;
			break;
		}

		$turn_cost = PLAYER_MESSAGE_IGNORE_COST;
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

		$enabled = false;

		$rs = $db->get_db()->query("select * from message_ignore where player = '$player_id' and `ignore` = '$player'");

		$rs->data_seek(0);
		if ($row = $rs->fetch_assoc()) {
			$enabled = true;
		}
		
		$expiration = PAGE_START_TIME + IGNORE_DURATION;

		if ($enabled) {

			if (!($st = $db->get_db()->prepare("delete from message_ignore where player = ? and `ignore` = ?"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("ii", $player_id, $player);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
				break;
			}
		}
		else {

			if (!($st = $db->get_db()->prepare("insert into message_ignore (player, `ignore`, expiration) values (?, ?, ?)"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("iii", $player_id, $player, $expiration);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
				break;
			}

			if ($db->get_db()->affected_rows <= 0) {
				$return_codes[] = 1142;
				break;
			}

			$read_time = PAGE_START_TIME;

			if (!($st = $db->get_db()->prepare("update message_targets set message_targets.read = ? where `target` = ? and sender = ?"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("iii", $read_time, $player_id, $player);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
				break;
			}
	
			if ($db->get_db()->affected_rows <= 0) {
				$return_codes[] = 1214;
				break;
			}	
		}
		
		if ($db->get_db()->affected_rows <= 0) {
			$return_codes[] = 1141;
			break;
		}

		$return_codes[] = 1143;

		unsetMessageWaiting();

	} while (false);