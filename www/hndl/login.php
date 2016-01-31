<?php
/**
 * Process the login request
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

	$return_page = 'login';
	
	do { /* Dummy loop for "break" support. */
		
		// Note that the javascript validators should have caught any 
		// inconsistencies so if an invalid form is submitted it can
		// be considered evidence of cheating.
		
		if (!isset($_POST['username']) || !validate_username($_POST['username'])) {
			$return_codes[] = '1002';
			break;
		}
		
		if (!isset($_POST['password1']) || !validate_password($_POST['password1'])) {
			$return_codes[] = '1003';
			break;
		}
		
		$username = $_POST['username'];
		$password1 = $_POST['password1'];
	
		// Get stuff from db
		$db = isset($db) ? $db : new DB;

		$rs = $db->get_db()->query("select password2 as salt from users where username = '" . strtolower($username) . "' limit 1");
		
		$salt = '';
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$salt = $row['salt'];
		}

		if ($salt == '') {
			$return_codes[] = '1007';
			error_log('Failed to obtain a salt from the database. The user might not exist.');
			break;
		}
		
		$hashed_password = hash('sha512', $salt . $password1);

		$rs = $db->get_db()->query("select record_id as id from users where username = '" . strtolower($username) . "' and password1 = '". $hashed_password ."' limit 1");
		
		$id = 0;
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$id = $row['id'];
		}

		if ($id > 0) {

			if (LOGIN_LOCKED || START_OF_ROUND - PAGE_START_TIME > 0) {

				$rs = $db->get_db()->query("select count(*) as count from users, user_fields where user_fields.`group` = 'admin' and users.username = '" . $username . "' and users.record_id = user_fields.user");

				$rs->data_seek(0);
				
				if ($row = $rs->fetch_assoc()) {
					if ($row['count'] <= 0) {
						$return_codes[] = 1120;
						break;
					}
				}
				else {
					$return_codes[] = 1120;
					break;
				}
			}


			$db->get_db()->query("update users set session_id = '". session_id() ."', session_time = '". PAGE_START_TIME ."' where record_id = '{$id}'");
			
			$_SESSION['uid'] = $id;
			$_SESSION['us'] = get_cookie_salt($id);
		}
		else {
			
			$return_codes[] = 1007;
			error_log('Failed to log in a user with the given password.');
			break;
		}
		
		break;
	} while (false);

	
	session_write_close();
	
	
?>