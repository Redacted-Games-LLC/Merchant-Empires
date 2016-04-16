<?php
/**
 * Handles transferring a gold key to another player's user
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

	$return_page = 'gold';
	
	do { // Dummy Loop

		if (!isset($_REQUEST['key']) || !validate_key($_REQUEST['key'])) {
			$return_codes[] = 1121;
			break;
		}

		$key = $_REQUEST['key'];

		if (!isset($_REQUEST['player']) || !validate_playername($_REQUEST['player'])) {
			$return_codes[] = 1011;
			break;
		}

		$player_name = $_REQUEST['player'];

		$user_id = 0;
		$user = 0;

		$db_user = isset($db_user) ? $db_user : new DB(true);

		$rs = $db_user->get_db()->query("select `user` from gold_keys where `key` = '". $key ."' and `used` <= 0 limit 1");
		$rs->data_seek(0);
		
		if ($row = $rs->fetch_assoc()) {
			$user_id = $row['user'];
		}
		else {
			$return_codes[] = 1123;
			break;
		}

		if ($user_id != USER_ID) {
			$return_codes[] = 1123;
			break;
		}

		$db = isset($db) ? $db : new DB;

		$rs = $db->get_db()->query("select user_players.user as user_id from user_players, players where user_players.player = players.record_id and lower(players.caption) = '". strtolower($player_name) ."'");
		$rs->data_seek(0);
		
		if ($row = $rs->fetch_assoc()) {
			$user = $row['user_id'];
		}
		else {
			$return_codes[] = 1130;
			break;
		}
		
		if (!($st = $db_user->get_db()->prepare('update gold_keys set `user` = ? where `key` = ? and `used` <= 0 and (`user` is null or `user` = ?)'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db_user->get_db()->errno . ") " . $db_user->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("isi", $user, $key, $user_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		if ($db_user->get_db()->affected_rows <= 0) {
			$return_codes[] = 1126;
			break;
		}


		$return_codes[] = 1131;
		
	} while (false);


?>