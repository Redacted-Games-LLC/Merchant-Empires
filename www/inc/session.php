<?php
/**
 * 
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

	include_once('inc/common.php');

	if (defined('CLI')) {

		define('USER_ID', 0);
		define('PLAYER_ID', 0);

	} else {
		if (isset($_SERVER['REMOTE_ADDR'])) {
			$length = strlen($_SERVER['REMOTE_ADDR']);
			
			if ($length < 3 || $length > 40) {
				die('Your IP address must be valid and within range to access game pages.');
			}
		} else {
			die('You must have a readable IP address to access game pages.');
		}
		
		if (defined('CLOSE_SESSION')) {
			prepare_session(CLOSE_SESSION);
		} else {
			prepare_session();
		}
	}



	function prepare_session($close = true) {
		
		session_start();
				
		// Check session for validity
		$supplied_session_id = session_id();
		
		$length = strlen($supplied_session_id);
		
		if ($length < 10 || $length > 128 || preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $supplied_session_id) < 1) {
			$_SESSION['uid'] = 0;
			$_SESSION['us'] = '';
			$_SESSION['pid'] = 0;
			$_SESSION['ps'] = '';
			session_write_close();
			error_log("Someone at {$_SERVER['REMOTE_ADDR']} attempted to spoof a session using a bad or missing id.");
			die('Session spoof attempt suspected and logged.');
		}
		
		// Check user for validity
		$supplied_user_id = 0;
		
		if (isset($_SESSION['uid']) && is_numeric($_SESSION['uid'])) {
			$supplied_user_id = 0 + $_SESSION['uid'];
		}
		
		if ($supplied_user_id <= 0) {
			define('USER_ID', 0);
			define('PLAYER_ID', 0);
			
			if ($close) {
				session_write_close();
			}
			
			return;
		}
		
		// Check the user salt to see if it matches
		$supplied_user_salt = $_SESSION['us'];
			
		if ($supplied_user_salt != get_cookie_salt($supplied_user_id)) {
			$_SESSION['uid'] = 0;
			$_SESSION['us'] = '';
			$_SESSION['pid'] = 0;
			$_SESSION['ps'] = '';
			session_write_close();
			error_log("Someone at {$_SERVER['REMOTE_ADDR']} attempted to spoof user #$supplied_user_id using a bad or missing salt.");
			die('User spoof attempt suspected and logged');
		}
		
		// Check player for validity
		$supplied_player_id = 0;
		
		if (isset($_SESSION['pid']) && is_numeric($_SESSION['pid'])) {
			$supplied_player_id = 0 + $_SESSION['pid'];
		}
		
		if ($supplied_player_id <= 0) {
			define('PLAYER_ID', 0);
			
			if ($close) {
				session_write_close();
			}
		}
		else {
			// Check the player salt for validity.
			$supplied_player_salt = $_SESSION['ps'];
			
			if ($supplied_player_salt != get_cookie_salt($supplied_player_id)) {
				$_SESSION['uid'] = 0;
				$_SESSION['us'] = '';
				$_SESSION['pid'] = 0;
				$_SESSION['ps'] = '';
				
				session_write_close();
				error_log("Someone at {$_SERVER['REMOTE_ADDR']} attempted to spoof player #$supplied_player_id using a bad or missing salt.");
				die('Player spoof attempt suspected and logged');
			}
		}
		
		// Salt and session appear ok. Let's hit the database.
		$db = isset($db) ? $db : new DB;
		
		$rs = $db->get_db()->query("select record_id as id from users where record_id = '{$supplied_user_id}' and session_id = '{$supplied_session_id}' limit 1");
		
		$user_id = 0;
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$user_id = $row['id'];
		}

		if ($user_id > 0) {
			// User ID is good.
			define('USER_ID', $user_id);
		}
		else {
			$_SESSION['uid'] = 0;
			$_SESSION['us'] = '';
			$_SESSION['pid'] = 0;
			$_SESSION['ps'] = '';
			
			session_write_close();
			error_log("Someone at {$_SERVER['REMOTE_ADDR']} attempted to spoof user #$supplied_user_id using session hijacking.");
			die('User spoof attempt suspected and logged');
		}
		
		if ($supplied_player_id > 0) {
			$rs = $db->get_db()->query("select player as id from user_players where player = '{$supplied_player_id}' and session_id = '{$supplied_session_id}' limit 1");
			
			$player_id = 0;
			$rs->data_seek(0);
			while ($row = $rs->fetch_assoc()) {
				$player_id = $row['id'];
			}

			if ($player_id > 0) {
				// Player ID is good.
				define('PLAYER_ID', $supplied_player_id);
			}
			else {
				$_SESSION['uid'] = 0;
				$_SESSION['us'] = '';
				$_SESSION['pid'] = 0;
				$_SESSION['ps'] = '';
				
				session_write_close();
				error_log("User {$user_id} at {$_SERVER['REMOTE_ADDR']} attempted to spoof player #$supplied_player_id using session hijacking.");
				die('Player spoof attempt suspected and logged');
			}
		}
		
		if ($close) {
			session_write_close();
		}
	}
	
	
	// Gets an unsecure salt for session pre-validation
	function get_cookie_salt($input) {
		return md5($_SERVER['REMOTE_ADDR'] . $input . GLOBAL_SALT);
	}
	
	
	
	function validate_username($username) {
		return preg_match('/^[a-zA-Z0-9]{2,16}$/', $username) > 0;
	}

	function validate_password($password) {
		return preg_match('/^.{6,32}$/', $password) > 0;
	}
	
	function validate_email($email) {
		// http://badsyntax.co/post/javascript-email-validation-rfc822
		return preg_match('/^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*$/', $email) > 0;
	}
	
	function validate_playername($playername) {
		return preg_match('/^[a-zA-Z0-9]{2,12}$/', $playername) > 0;
	}
	
	
	function get_user_field($group, $key = null, $default = null, $db = null) {

		static $user_fields = null;

		if ($user_fields == null) {
			$user_fields = array();

			$db = $db == null ? new DB : $db;

			$rs = $db->get_db()->query("select * from user_fields where user = '". USER_ID ."'");

			$rs->data_seek(0);
			while ($row = $rs->fetch_assoc()) {
				$user_fields[$row['group']][$row['key']] = $row['value'];
			}
		}

		if (preg_match('/^[a-zA-Z0-9]{1,16}$/', $group) <= 0) {
			return false;
		}

		if ($key == null) {
			return isset($user_fields[$group]);
		}

		if (preg_match('/^[a-zA-Z0-9]{1,16}$/', $key) <= 0) {
			return false;
		}
		
		if (!isset($user_fields[$group][$key])) {
			return $default;	
		}
		
		return $user_fields[$group][$key];
	}
	
	
	function set_user_field($group, $key = null, $value = null) {

		global $db;
		$db = $db == null ? new DB : $db;

		if (preg_match('/^[a-zA-Z0-9]{1,16}$/', $group) <= 0) {
			error_log(__FILE__ . ':' . __LINE__ . ' ');
			return false;
		}

		if (preg_match('/^[a-zA-Z0-9]{1,16}$/', $key) <= 0) {
			return false;
		}
		
		if (!($st = $db->get_db()->prepare("insert into user_fields (group, key, value) values (?,?,?)"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			return false;
		}

		$st->bind_param("sss", $group, $key, $value);
		
		if (!$st->execute()) {
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			$return_codes[] = 1006;
			return false;
		}

		return true;
	}


?>