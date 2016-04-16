<?php
/**
 * Handles signing up a user for the game
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

		$username = $_POST['username'];
		
		// Note that the javascript validators should have caught any 
		// inconsistencies so if an invalid form is submitted it can
		// be considered evidence of cheating.
		
		if (!validate_username($username)) {
			$return_codes[] = '1002';
			// TODO: Log possible cheat attempt
			break;
		}
		
		if (LOGIN_LOCKED || START_OF_ROUND - PAGE_START_TIME > 0) {
			if (SIGNUP_ADMIN != $username) {
				$return_codes[] = 1120;
				break;
			}
		}
		
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		$email = $_POST['email'];
		
		if (!validate_password($password1)) {
			// TODO: Log possible cheat attempt
			$return_codes[] = '1003';
			break;
		}
		
		if ($password2 != $password1) {
			$return_codes[] = '1004';
			// TODO: Log possible cheat attempt
			break;
		}
		
		if (!validate_email($email)) {
			$return_codes[] = '1005';
			// TODO: Log possible cheat attempt
			break;
		}
		
		$try_x = $_REQUEST['try_x'];
		$try_y = $_REQUEST['try_y'];
		
		if (!is_numeric($try_x) || $try_x <= 0 || !is_numeric($try_y) || $try_y <= 0) {
			$return_codes[] = 1182;
			break;
		}

		$test_x = $_REQUEST['test_x'];
		$test_y = $_REQUEST['test_y'];
				
		if (!is_numeric($test_x) || $test_x <= 0 || !is_numeric($test_y) || $test_y <= 0) {
			$return_codes[] = 1182;
			break;
		}

		$test_dx = $_REQUEST['test_dx'];
		$test_dy = $_REQUEST['test_dy'];

		if (!is_numeric($test_dx) || !is_numeric($test_dy)) {
			$return_codes[] = 1182;
			break;
		}

		if ($test_x + $test_dx != $try_x || $test_y + $test_dy != $try_y) {
			$return_codes[] = 1182;
			break;
		}

		$username = strtolower($username);
		$session_id = session_id();
		$salt = hash('sha512', microtime() . GLOBAL_SALT . $username . $_SERVER["HTTP_USER_AGENT"]);
		$hashed_password = hash('sha512', $salt . $password1);
		$time = PAGE_START_TIME;
		
		$db_user = isset($db_user) ? $db_user : new DB(true);
		
		$rs = $db_user->get_db()->query("select record_id from users where username = '$username' limit 1");
		
		$rs->data_seek(0);
		if ($row = $rs->fetch_assoc()) {
			$return_codes[] = 1071;
			break;
		}

		if (!($st = $db_user->get_db()->prepare('INSERT INTO users (username, password1, password2, session_id, session_time) VALUES (?,?,?,?,?)'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db_user->get_db()->errno . ") " . $db_user->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("ssssi", $username, $hashed_password, $salt, $session_id, $time);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}
		
		$id = $db_user->last_insert_id('users');
		
		if ($id > 0) {
			// Successfully signed up a user.
			$_SESSION['uid'] = $id;
			$_SESSION['form_id'] = substr(hash('sha256', microtime() . $id . $_SERVER["HTTP_USER_AGENT"]), 16, 32);
		}
		else {
			$return_codes[] = '1006';
			error_log(__FILE__ . '::' . __LINE__ . " Failed to get last insert id after successful insertion. (" . $db_user->get_db()->errno . ") " . $db_user->get_db()->error);
			// TODO: Better way to deal with this situation.
			break;
		}
		
		$return_page = 'viewport';

		if (strtolower(SIGNUP_ADMIN) == $username) {
			if (set_user_field($id, 'admin', 'admin', '1') && set_user_field($id, 'admin', 'users', '1')) {
				$return_page = 'admin';
			}
			else {
				$return_codes[] = 1179;
			}
		}
		
	} while (false);

	
	session_write_close();
	
	

	
	
	
	
?>