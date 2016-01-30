<?php
/**
 * Handles adding a room type to the database.
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
		
		// Did we get a valid caption?

		$caption = '';

		if (!isset($_REQUEST['caption']) || strlen($_REQUEST['caption']) <= 1) {
				$return_codes[] = 1111;
				break;
		}
		else {
			if (trim($_REQUEST['caption']) != $_REQUEST['caption']) {
				$return_codes[] = 1111;
				break;
			}

			if (str_replace('  ', ' ', $_REQUEST['caption']) != $_REQUEST['caption']) {
				$return_codes[] = 1111;
				break;
			}

			if (!preg_match('/^[a-zA-Z0-9-_\'" ]{1,24}$/i', $_REQUEST['caption'])) {
				$return_codes[] = 1110;
				break;
			}

			$caption = $_REQUEST['caption'];
		}


		define('MINIMUM_ROOM_INFO', 1);
		include_once('inc/rooms.php');

		$safe_caption = str_replace(' ', '_', strtolower($caption));

		if (isset($spacegame['room_index'][$safe_caption])) {
			$return_codes[] = 1169;
			break;
		}

		$db = isset($db) ? $db : new DB;

		if (!($st = $db->get_db()->prepare("insert into room_types (caption, build_limit) values (?, 0)"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("s", $caption);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}
		
		$room_id = $db->last_insert_id('room_types');

		if ($room_id <= 0) {
			$return_codes[] = 1170;
			break;
		}

		$return_page = 'admin';
		$return_vars['page'] = 'room';
		$return_vars['room'] = $safe_caption;
		$return_codes[] = 1171;

	} while (false);


?>