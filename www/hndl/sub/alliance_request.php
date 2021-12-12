<?php
/**
 * Handles requesting to join an alliance
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
	include_once('inc/alliance.php');
	include_once('inc/msg_functions.php');

	$return_vars['page'] = 'list';
	$return_vars['alliance_id'] = 0;

	do { // Dummy Loop
		
		if ($spacegame['player']['alliance'] > 0) {
			$return_codes[] = 1082;
			break;
		}

		if ($spacegame['active_invites_count'] >= ALLIANCE_REQUEST_LIMIT) {
			$return_codes[] = 1089;
			break;
		}

		if (!isset($_REQUEST['alliance_id']) || !is_numeric($_REQUEST['alliance_id'])) {
			$return_codes[] = 1084;
			break;
		}

		$alliance_id = $_REQUEST['alliance_id'];

		if (!isset($spacegame['alliances'][$alliance_id])) {
			$return_codes[] = 1084;
			break;
		}

		$return_vars['page'] = 'members';
		$return_vars['alliance_id'] = $alliance_id;

		if ($spacegame['alliances'][$alliance_id]['recruiting'] <= 0) {
			$return_codes[] = 1092;
			break;
		}

		if (isset($spacegame['invite_alliances'][$alliance_id])) {
			$return_codes[] = 1090;
			break;
		}

		$db = isset($db) ? $db : new DB;

		$time = PAGE_START_TIME;
		$player_id = PLAYER_ID;

		if (!($st = $db->get_db()->prepare('insert into alliance_invitations (player, alliance, requested) values (?, ?, ?)'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("iii", $player_id, $alliance_id, $time);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		$return_codes[] = 1091;

		// Notify alliance leader of recruitment request
		$message = "Requesting to join your alliance.";
		$targets = array();
		$targets[] = $spacegame['alliances'][$alliance_id]['founder'];
		send_message($message, $targets, MESSAGE_EXPIRATION, 1);

	} while (false);