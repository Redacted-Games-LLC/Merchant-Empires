<?php
/**
 * Handles sending a message to a specific player
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

	$return_vars['page'] = 'player';
	
	do { // Dummy Loop
		
		if (MESSAGE_TURN_COST > $spacegame['player']['turns']) {
			$return_codes[] = 1018;
			break;
		}

		if (!isset($_REQUEST['player']) || !validate_playername($_REQUEST['player'])) {
			$return_codes[] = 1011;
			break;
		}

		if (!isset($_REQUEST['message'])) {
			$return_codes[] = 1136;
			break;
		}

		$len = strlen($_REQUEST['message']);

		if ($len <= 0) {
			$return_codes[] = 1136;
			break;
		}

		if ($len > MAXIMUM_MESSAGE_LENGTH) {
			$return_codes[] = 1149;
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

		$targets = array();
		$targets[] = $player;

		$turn_cost = MESSAGE_TURN_COST;
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

		send_message($_REQUEST['message'], $targets, MESSAGE_EXPIRY, 1);

		$return_codes[] = 1137;

	} while (false);


?>