<?php
/**
 * 
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

	define('CLOSE_SESSION', false);
	include_once('inc/page.php');

	if ((isset($_SESSION['form_id'])) && (!isset($_REQUEST['form_id']) || $_SESSION['form_id'] != $_REQUEST['form_id'])) {
		header('Location: viewport.php?rc=1181');
		die();
	}

	$return_page = 'select_player';
	
	do { /* Dummy loop for "break" support. */
		
		$id = $_POST['player_id'];
		
		if ((!is_numeric($id)) || $id < 0) {
			$return_codes[] = '1014';
			break;
		}
		
		$record_id = 0;
		
		$db = isset($db) ? $db : new DB;
		$rs = $db->get_db()->query("select record_id from user_players where user = '" . USER_ID . "' and player = '" . $id . "' limit 1");
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$record_id = $row['record_id'];
		}
		
		if ($record_id <= 0) {
			// TODO: Log possible cheat attempt
			$return_codes[] = '1014';
			break;
		}
		
		$user_id = USER_ID;
		$session_id = session_id();
		$time = PAGE_START_TIME;
		
		if (!($st = $db->get_db()->prepare('update user_players set session_id = ?, session_time = ? where user = ? and player = ?'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("siii", $session_id, $time, $user_id, $id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}
	
		$_SESSION['pid'] = $id;

	} while (false);
	
	session_write_close();