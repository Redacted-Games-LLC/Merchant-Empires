<?php
/**
 * Handles adding a room requirement to the database.
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

		$good = 0;
		$count = 0;
		$research = 0;
		$build = 0;
	
		include_once('inc/goods.php');

		if (isset($_REQUEST['good']) && $_REQUEST['good'] != '[none]') {
			if (!isset($spacegame['good_index'][$_REQUEST['good']])) {
				$return_codes[] = 1042;
				break;
			}
			elseif (!isset($_REQUEST['amount']) || !is_numeric($_REQUEST['amount']) || $_REQUEST['amount'] <= 0) {
				$return_codes[] = 1027;
				break;
			}
			else {
				$good = $spacegame['good_index'][$_REQUEST['good']];
				$amount = $_REQUEST['amount'];
			}
		}

		include_once('inc/research.php');

		if (isset($_REQUEST['research']) && $_REQUEST['research'] != '[none]') {
			if (!isset($spacegame['research_index'][$_REQUEST['research']])) {
				$return_codes[] = 1067;
				break;
			}
			else {
				$research = $spacegame['research_index'][$_REQUEST['research']];
			}
		}

		if (isset($_REQUEST['build']) && $_REQUEST['build'] != '[none]') {
			if (!isset($spacegame['room_index'][$_REQUEST['build']])) {
				$return_codes[] = 1066;
				break;
			}
			else {
				$build = $spacegame['room_index'][$_REQUEST['build']];
			}
		}

		quit($GLOBALS);

		$db = isset($db) ? $db : new DB;

		
	} while (false);


?>