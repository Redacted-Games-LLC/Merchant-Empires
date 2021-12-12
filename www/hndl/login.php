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
		$db_user = isset($db_user) ? $db_user : new DB(true);

		$ip = inet_ntop(inet_pton(getClientIP()));
		$id = 0;
		$salt = '';

		$rs = $db_user->get_db()->query("select record_id, password2 as salt from users where username = '" . strtolower($username) . "' limit 1");
		
		$rs->data_seek(0);
		if ($row = $rs->fetch_assoc()) {
			$salt = $row['salt'];
			$id = $row['record_id'];
		}

		$time = PAGE_START_TIME;
		$attempts = array();

		$rs = $db_user->get_db()->query("select * from login_history where ip = '$ip' order by time desc limit 3");
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$attempts[$row['record_id']] = $row;
		}

		$attempt = 0;
		$attempt_time = 0;
		$tier = 0;

		foreach ($attempts as $record_id => $row) {
			$attempt_time = $row['time'];

			if ($row['attempts'] >= LOGIN_ATTEMPTS) {
				$tier += 1;
			}

			if ($tier > 0) {
				continue;
			}

			if ($attempt_time < PAGE_START_TIME - LOGIN_ATTEMPT_TIME) {
				break;
			}

			if ($attempt > 0) {
				continue;
			}

			$attempt = $record_id;
		}

		if ($tier > 0) {
			$timeout = LOGIN_BAN_TIER_1;

			if ($tier == 2) {
				$timeout = LOGIN_BAN_TIER_2;
			}
			else if ($tier >= 3) {
				$timeout = LOGIN_BAN_TIER_3;
			}

			if ($attempt_time + $timeout > PAGE_START_TIME) {
				$return_codes[] = 1180;
				break;
			}
		}
		
		if ($attempt > 0) {
			// Update existing login with new attempt.

			if (!($st = $db_user->get_db()->prepare('update login_history set time = ?, attempts = attempts + 1 where record_id = ?'))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db_user->get_db()->errno . ") " . $db_user->get_db()->error);
				$return_codes[] = 1006;
				break;
			}

			$st->bind_param('ii', $time, $attempts[$attempt]['record_id']);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
				break;
			}
		}
		else {
			// Insert new login attempt
			$st = null;

			if ($id > 0) {
				if (!($st = $db_user->get_db()->prepare('insert into login_history (user, time, ip, attempts) values (?, ?, ?, 1)'))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db_user->get_db()->errno . ") " . $db_user->get_db()->error);
					$return_codes[] = 1006;
					break;
				}

				$st->bind_param('iis', $id, $time, $ip);
			}
			else {
				if (!($st = $db_user->get_db()->prepare('insert into login_history (user, time, ip, attempts) values (NULL, ?, ?, 1)'))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db_user->get_db()->errno . ") " . $db_user->get_db()->error);
					$return_codes[] = 1006;
					break;
				}

				$st->bind_param('is', $time, $ip);
			}
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
				break;
			}
		}

		if ($salt == '') {
			$return_codes[] = '1007';
			break;
		}
		
		$hashed_password = hash('sha512', $salt . $password1);

		$rs = $db_user->get_db()->query("select record_id as id, ban_code, ban_timeout from users where username = '" . strtolower($username) . "' and password1 = '". $hashed_password ."' limit 1");
		
		$rs->data_seek(0);
		if ($row = $rs->fetch_assoc()) {
			$id = $row['id'];
		}
		else {
			$return_codes[] = 1007;
			break;
		}

		if ($id > 0) {

			if (LOGIN_LOCKED || ((START_OF_ROUND - PAGE_START_TIME > 0) && !DEV_ROUND)) {

				$rs = $db_user->get_db()->query("select count(*) as count from users, user_fields where user_fields.`group` = 'admin' and users.username = '" . $username . "' and users.record_id = user_fields.user");

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

			$session_id = session_id();
			
			if (!($st = $db_user->get_db()->prepare("update users set session_id = ?, session_time = ? where record_id = ?"))) {
				error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db_user->get_db()->errno . ") " . $db_user->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("sii", $session_id, $time, $id);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db_user->get_db()->errno . ") " . $db_user->get_db()->error);
				break;
			}
			
			$_SESSION['uid'] = $id;
			$_SESSION['form_id'] = substr(hash('sha256', microtime() . $id . getClientIP()), 16, 32);
		}
		else {
			
			$return_codes[] = 1007;
			break;
		}		
	} while (false);

	session_write_close();