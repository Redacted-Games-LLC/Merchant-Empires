<?php
/**
 * Handles sending a subspace broadcast message
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

	$return_vars['page'] = 'subspace';
	
	do { // Dummy Loop
		
		if (SUBSPACE_MESSAGE_TURN_COST > $spacegame['player']['turns']) {
			$return_codes[] = 1018;
			break;
		}

		if (!isset($_REQUEST['message']) || strlen($_REQUEST['message']) <= 0) {
			$return_codes[] = 1136;
			break;
		}

		$targets = array();
		$target_count = 0;

		$db = isset($db) ? $db : new DB;

		$rs = $db->get_db()->query("select record_id from players where last_turns >= '" . (PAGE_START_TIME - ONLINE_PLAYER_TIME) . "'");

		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$targets[] = $row['record_id'];
			$target_count++;
		}

		if ($target_count <= 0) {
			$return_codes[] = 1138;
			break;
		}

		$turn_cost = SUBSPACE_MESSAGE_TURN_COST;
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

		send_message($_REQUEST['message'], $targets, SUBSPACE_MESSAGE_EXPIRY, 3);

		$return_codes[] = 1137;

	} while (false);


?>