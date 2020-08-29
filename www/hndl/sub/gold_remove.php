<?php
/**
 * Handles removing a gold key from user storage
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
		$user = 0;

		$db_user = isset($db_user) ? $db_user : new DB(true);

		$rs = $db_user->get_db()->query("select `user` from gold_keys where `key` = '". $key ."' and `used` <= 0 limit 1");
		$rs->data_seek(0);
		
		if ($row = $rs->fetch_assoc()) {
			$user = $row['user'];
		}
		else {
			$return_codes[] = 1123;
			break;
		}

		if ($user <= 0) {
			$return_codes[] = 1123;
			break;
		}
		elseif ($user != USER_ID) {
			$return_codes[] = 1123;
			break;
		}

		$user_id = USER_ID;
		
		if (!($st = $db_user->get_db()->prepare('update gold_keys set `user` = null where `key` = ? and used <= 0 and `user`  = ?'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db_user->get_db()->errno . ") " . $db_user->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("si", $key, $user_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		if ($db_user->get_db()->affected_rows <= 0) {
			$return_codes[] = 1126;
			break;
		}

		$return_codes[] = 1127;
		
	} while (false);