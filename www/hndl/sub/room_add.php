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
		
		if (!isset($_REQUEST['room']) || !isset($spacegame['room_index'][$_REQUEST['room']])) {
			$return_codes[] = 1166;
			break;
		}

		$room = $spacegame['room_types'][$spacegame['room_index'][$_REQUEST['room']]];
		$return_vars['page'] = 'room';
		$return_vars['room'] = $room['safe_caption'];





		$db = isset($db) ? $db : new DB;

		
	} while (false);


?>