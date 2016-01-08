<?php
/**
 * Handles hiding and unhiding messages.
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
	$return_vars['p'] = $_REQUEST['p'];
	$return_vars['pp'] = $_REQUEST['pp'];
	
	do { // Dummy Loop
		
		if (!isset($_REQUEST['message']) || !is_numeric($_REQUEST['message']) || $_REQUEST['message'] <= 0) {
			$return_codes[] = 1044;
			break;
		}

		$player_id = PLAYER_ID;
		$message = $_REQUEST['message'];
		$read = 0;

		$db = isset($db) ? $db : new DB;

		$rs = $db->get_db()->query("select * from message_targets where target = '$player_id' and message = '$message'");
		
		$rs->data_seek(0);
		if ($row = $rs->fetch_assoc()) {
			$read = $row['read'];
		}
		else {
			$return_codes[] = 1145;
			break;
		}

		if ($read <= 0) {
			$read = PAGE_START_TIME;
		}
		else {
			$read = 0;
		}



		if (!($st = $db->get_db()->prepare("update message_targets set `read` = ? where `target` = ? and message = ?"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("iii", $read, $player_id, $message);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		if ($db->get_db()->affected_rows <= 0) {
			$return_codes[] = 1146;
			break;
		}			
		

		$return_codes[] = 1147;

		

	} while (false);


?>