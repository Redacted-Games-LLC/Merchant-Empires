<?php
/**
 * Handles room/construction related tasks.
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

	include_once('inc/page.php');
	include_once('inc/game.php');

	$return_page = 'build';
	

	do { // Dummy loop

		switch ($_REQUEST['subtask']) {

			case 'add':
			case 'edit':
			case 'delete':
			case 'add_requirement':
			case 'delete_requirement':

				$return_page = 'admin';
				$return_vars['page'] = 'build';

				if (!get_user_field(USER_ID, 'admin', 'build')) {
					$return_codes[] = 1030;
					break 2;
				}

				include_once('inc/rooms.php');
			
				$subtask_page = $_REQUEST['subtask'];
				$subtask_file = "hndl/sub/room_{$subtask_page}.php";

				if (!file_exists($subtask_file)) {
					$return_codes[] = 1041;
					error_log(__FILE__ . '::' . __LINE__ . ' Valid subtask does not have an include.');
					break;
				}
				
				include_once($subtask_file);
				break;

			default:
				$return_codes[] = 1041;
				break 2;
 		}
	} while (false);


?>