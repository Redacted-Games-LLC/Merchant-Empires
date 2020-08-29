<?php
/**
 * Handles user manipulation actions
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

	$return_page = 'admin';
	$return_vars['page'] = 'users';	

	do { // Dummy loop

		if (!get_user_field(USER_ID, 'admin', 'users')) {
			$return_codes[] = 1030;
			break;
		}

		$request_subtask = $_REQUEST['subtask'];

		if (!isset($request_subtask)) {
			$return_codes[] = 1041;
		}

		switch ($request_subtask) {

			case 'field':

				$sub_page = $request_subtask;
				$return_vars['page'] = $sub_page;
				$sub_file = "hndl/sub/users_{$sub_page}.php";

				if (!file_exists($sub_file)) {
					$return_codes[] = 1041;
					error_log(__FILE__ . '::' . __LINE__ . ' Valid subtask does not have an include.');
					break;
				}
				
				include_once($sub_file);
				break;

			default:
				$return_codes[] = 1041;
				break;
 		}
	} while (false);