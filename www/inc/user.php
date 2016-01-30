<?php
/**
 * Loads information about a specific user
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

	include_once('inc/common.php');
	include_once('inc/game.php');

	if (!get_user_field(USER_ID, 'admin', 'users')) {
		header('Location: viewport.php?rc=1030');
		die();
	}

	do { // Dummy Loop

		if (!isset($_REQUEST['user']) || !validate_username($_REQUEST['user'])) {
			break;
		}

		
		$db = isset($db) ? $db : new DB;

		$rs = $db->get_db()->query("select record_id, username, session_time from users where lower(username) = lower('". $_REQUEST['user'] ."')");

		$rs->data_seek(0);

		if ($row = $rs->fetch_assoc()) {
			$spacegame['user_info'] = $row;
		}
		else {
			break;
		}

		$spacegame['user_info']['players'] = array();
		$spacegame['user_info']['player_count'] = 0;

		$rs = $db->get_db()->query("select players.caption, user_players.session_time from players, user_players where user_players.player = players.record_id and user_players.user = '". $spacegame['user_info']['record_id'] ."'");

		$rs->data_seek(0);

		while ($row = $rs->fetch_assoc()) {
			$spacegame['user_info']['players'][] = $row;
			$spacegame['user_info']['player_count']++;
		}

		$spacegame['user_info']['fields'] = get_user_field($spacegame['user_info']['record_id']);

	} while (false);


?>