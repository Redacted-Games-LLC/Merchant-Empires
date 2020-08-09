<?php
/**
 * Handles sending a message to a specific alliance
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

	$return_vars['page'] = 'alliance';
	
	do { // Dummy Loop
		if (ALLIANCE_MESSAGE_TURN_COST > $spacegame['player']['turns']) {
			$return_codes[] = 1018;
			break;
		}

		if (!isset($_REQUEST['alliance']) || !validate_alliancename($_REQUEST['alliance'])) {
			$return_codes[] = 1080;
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

		$alliance_id = 0;

		$rs = $db->get_db()->query("select record_id from alliances where lower(`caption`) = '". strtolower($_REQUEST['alliance']) ."'");
		
		$rs->data_seek(0);
		if ($row = $rs->fetch_assoc()) {
			$alliance_id = $row['record_id'];
		}
		else {
			$return_codes[] = 1084;
			break;
		}

		$targets = array();
		$target_count = 0;

		$rs = $db->get_db()->query("select record_id from players where alliance = '" . $alliance_id . "'");

		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$targets[] = $row['record_id'];
			$target_count++;
		}

		if ($target_count <= 0) {
			$return_codes[] = 1138;
			break;
		}

		$turn_cost = ALLIANCE_MESSAGE_TURN_COST * $target_count;
		$player_id = PLAYER_ID;

		if ($turn_cost > $spacegame['player']['turns']) {
			$return_codes[] = 1018;
			break;
		}

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

		send_message($_REQUEST['message'], $targets, MESSAGE_EXPIRATION, 2);

		$return_codes[] = 1137;

	} while (false);