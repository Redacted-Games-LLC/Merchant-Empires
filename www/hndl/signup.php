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

		if (LOGIN_LOCKED) {
			$return_codes[] = 1120;
			break;
		}
		
		$username = $_POST['username'];
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		$email = $_POST['email'];
		
		// Note that the javascript validators should have caught any 
		// inconsistencies so if an invalid form is submitted it can
		// be considered evidence of cheating.
		
		if (!validate_username($username)) {
			$return_codes[] = '1002';
			// TODO: Log possible cheat attempt
			break;
		}
		
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
		
		$username = strtolower($username);
		$session_id = session_id();
		$salt = hash('sha256', microtime() . GLOBAL_SALT . $username);
		$hashed_password = hash('sha512', $salt . $password1);
		$time = PAGE_START_TIME;
		
		$db = isset($db) ? $db : new DB;
		
		$rs = $db->get_db()->query("select record_id from users where username = '$username' limit 1");
		
		$rs->data_seek(0);
		if ($row = $rs->fetch_assoc()) {
			$return_codes[] = 1071;
			break;
		}

		if (!($st = $db->get_db()->prepare('INSERT INTO users (username, password1, password2, session_id, session_time) VALUES (?,?,?,?,?)'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("ssssi", $username, $hashed_password, $salt, $session_id, $time);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}
		
		$id = $db->last_insert_id('users');
		
		if ($id > 0) {
			// Successfully signed up a user.
			$_SESSION['uid'] = $id;
			$_SESSION['us'] = get_cookie_salt($id);
		}
		else {
			$return_codes[] = '1006';
			error_log(__FILE__ . '::' . __LINE__ . " Failed to get last insert id after successful insertion. (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			// TODO: Better way to deal with this situation.
			break;
		}
		
		$return_page = 'viewport';
		break;
		
	} while (false);

	
	session_write_close();
	
	

	
	
	
	
?>