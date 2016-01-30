<?php
/**
 * Handles deleting a room type requirement from the database.
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

	$return_page = 'admin';
	$return_vars['page'] = 'build';

	do { // Dummy Loop
		
		if (!isset($_REQUEST['room']) || !isset($spacegame['room_index'][$_REQUEST['room']])) {
			$return_codes[] = 1166;
			break;
		}

		$room = $spacegame['room_index'][$_REQUEST['room']];
		$return_vars['page'] = 'room';
		$return_vars['room'] = $spacegame['room_types'][$room]['safe_caption'];
		
		$db = isset($db) ? $db : new DB;

		if (!($st = $db->get_db()->prepare("delete from room_requirements where room = ?"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("i", $room);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}
		
		if ($db->get_db()->affected_rows <= 0) {
			$return_codes[] = 1176;
			break;
		}

		$return_codes[] = 1177;

	} while (false);


?>