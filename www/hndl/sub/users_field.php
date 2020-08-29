<?php
/**
 * Handles altering user fields
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
	include_once('inc/page.php');
	
	$return_page = 'admin';
	$return_vars['page'] = 'users';

	do { // Dummy Loop

		if (!get_user_field(USER_ID, 'admin', 'users')) {
			$return_codes[] = 1030;
			break;
		}

		if (!isset($_REQUEST['user']) || !validate_username($_REQUEST['user'])) {
			$return_codes[] = 1002;
			break;
		}

		$return_vars['page'] = 'user';
		$return_vars['user'] = $_REQUEST['user'];

		if (!isset($_REQUEST['group']) || !validate_groupname($_REQUEST['group'])) {
			$return_codes[] = 1150;
			break;
		}
	
		if (($_REQUEST['group'] == 'admin') && (!get_user_field(USER_ID, 'admin', 'admin'))) {
			$return_codes[] = 1153;
			break;
		}

		if (!isset($_REQUEST['key']) || !validate_keyname($_REQUEST['key'])) {
			$return_codes[] = 1151;
			break;
		}

		if (!isset($_REQUEST['value']) || !validate_value($_REQUEST['value'])) {
			$return_codes[] = 1152;
			break;
		}

		$db = isset($db) ? $db : new DB(true);

		$user_id = 0;
		$rs = $db->get_db()->query("select record_id from users where username = '". $_REQUEST['user'] ."'");

		$rs->data_seek(0);
		if ($row = $rs->fetch_assoc()) {
			$user_id = $row['record_id'];
		}
		else {
			$return_codes[] = 1156;
			break;
		}

		if (isset($_REQUEST['btn_update_x'])) {

			if (set_user_field($user_id, $_REQUEST['group'], $_REQUEST['key'], null)) {
				if (set_user_field($user_id, $_REQUEST['group'], $_REQUEST['key'], $_REQUEST['value'])) {
					$return_codes[] = 1047;
					break;
				}
				else {
					$return_codes[] = 1055;
					break;
				}
			}
			else {
				$return_codes[] = 1154;
				break;
			}
		}	
		elseif (isset($_REQUEST['btn_delete_x'])) {
				
			if (set_user_field($user_id, $_REQUEST['group'], $_REQUEST['key'], null)) {
				$return_codes[] = 1047;
				break;
			}
			else {
				$return_codes[] = 1154;
				break;
			}
		}
		elseif (isset($_REQUEST['btn_add_x'])) {
				
			if (set_user_field($user_id, $_REQUEST['group'], $_REQUEST['key'], $_REQUEST['value'])) {
				$return_codes[] = 1049;
				break;
			}
			else {
				$return_codes[] = 1155;
				break;
			}
		}
		else {
			$return_codes[] = 1068;
			break;
		}

	} while (false);