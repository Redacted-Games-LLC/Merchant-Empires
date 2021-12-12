<?php
/**
 * Session handling stuff.
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
		if (getClientIP() != null) {
			$length = strlen(getClientIP());
			
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

	// Detect Internet Explorer 11 - flagged for known compatibility issues.
	// Not detecting older IE browsers as Microsoft has already ended support for them.
	// Ref: https://docs.microsoft.com/en-us/lifecycle/faq/internet-explorer-microsoft-edge
	function detect_iexplore11() {
		return (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0') !== false));
	}

	function prepare_session($close = true) {
		
		session_start();
				
		// Check session for validity
		$supplied_session_id = session_id();
		
		$length = strlen($supplied_session_id);
		
		if ($length < 10 || $length > 128 || preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $supplied_session_id) < 1) {
			$_SESSION['uid'] = 0;
			$_SESSION['pid'] = 0;
			session_write_close();
			header('Location: login.php');
			die('');
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
		
		$user_id = 0;

		$db_user = isset($db_user) ? $db_user : new DB(true);
		$db = isset($db) ? $db : new DB();

		$rs = $db_user->get_db()->query("select record_id from users where record_id = '{$supplied_user_id}' and session_id = '{$supplied_session_id}' limit 1");

		$rs->data_seek(0);
		if ($row = $rs->fetch_assoc()) {
			define('USER_ID', $row['record_id']);
		}
		else {
			$_SESSION = array();
			session_write_close();
			
			if (!defined('USER_ID')) {
				define('USER_ID', 0);
			}
			
			return;
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
				$_SESSION['pid'] = 0;
				session_write_close();
				header('Location: select_player.php');
				die();
			}
		}

		if ($close) {
			session_write_close();
		}
	}
	
	function validate_username($username) {
		return preg_match('/^[a-zA-Z0-9_]{2,16}$/', $username) > 0;
	}

	function validate_password($password) {
		$len = strlen($password);
		return ($len >= 6 && $len <= 128);
	}

	function validate_email($email) {
		if (strlen($email) <= 0) {
			return true;
		}

		if (strlen($email) > 128) {
			return false;
		}

		// https://stackoverflow.com/a/42037557
		$emailIsValid = false;
		if (!empty($email)) {
			$domain = ltrim(stristr($email, '@'), '@') . '.';
			$user   = stristr($email, '@', TRUE);

			if (!empty($user) && !empty($domain) && checkdnsrr($domain)) {
				$emailIsValid = true;
			}
		}
		return $emailIsValid;
	}
	
	function validate_playername($playername) {
		return preg_match('/^[a-zA-Z0-9_]{2,12}$/', $playername) > 0;
	}
	
	function validate_alliancename($alliancename) {
		return preg_match('/^[a-zA-Z0-9 ]{2,24}$/', $alliancename) > 0;
	}

	function validate_key($key) {
		return preg_match('/^[-a-z0-9]{'.MINIMUM_KEY_LENGTH.','.MAXIMUM_KEY_LENGTH.'}$/i', $key) > 0;
	}

	function validate_groupname($group) {
		return preg_match('/^[a-zA-Z0-9_]{1,16}$/', $group) > 0;
	}

	function validate_keyname($key) {
		return preg_match('/^[a-zA-Z0-9_]{1,16}$/', $key) > 0;
	}

	function validate_value($value) {
		$len = strlen($value);

		return $len > 0 && $len <= 64;
	}
	
	function get_user_field($user, $group = null, $key = null, $default = null, $db = null) {

		static $user_fields = null;

		if ($user_fields == null) {
			$user_fields = array();
		}

		if (!isset($user_fields[$user])) {
			
			global $db_user;
			$db_user = isset($db_user) ? $db_user : new DB(true);

			$rs = $db_user->get_db()->query("select * from user_fields where user = '". $user ."'");

			$rs->data_seek(0);
			while ($row = $rs->fetch_assoc()) {
				$user_fields[$user][$row['group']][$row['key']] = $row['value'];
			}
		}

		if ($group == null) {
			return $user_fields[$user];
		}

		if (preg_match('/^[a-zA-Z0-9]{1,16}$/', $group) <= 0) {
			return false;
		}

		if ($key == null) {
			return isset($user_fields[$user][$group]);
		}

		if (preg_match('/^[a-zA-Z0-9]{1,16}$/', $key) <= 0) {
			return false;
		}
		
		if (!isset($user_fields[$user][$group][$key])) {
			return $default;	
		}
		
		return $user_fields[$user][$group][$key];
	}	
	
	function set_user_field($user, $group, $key, $value = null) {

		global $db_user;
		$db_user = isset($db_user) ? $db_user : new DB(true);

		if (preg_match('/^[a-zA-Z0-9]{1,16}$/', $group) <= 0) {
			return false;
		}

		if (preg_match('/^[a-zA-Z0-9]{1,16}$/', $key) <= 0) {
			return false;
		}
	
		if ($value == null) {

			if (!($st = $db_user->get_db()->prepare("delete from user_fields where `user` = ? and `group` = ? and `key` = ?"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db_user->get_db()->errno . ") " . $db_user->get_db()->error);
				return false;
			}

			$st->bind_param("iss", $user, $group, $key);
			
			if (!$st->execute()) {
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
				return false;
			}
		}
		else {

			if (!($st = $db_user->get_db()->prepare("insert into user_fields (`user`, `group`, `key`, `value`) values (?, ?,?,?)"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db_user->get_db()->errno . ") " . $db_user->get_db()->error);
				return false;
			}

			$st->bind_param("isss", $user, $group, $key, $value);
			
			if (!$st->execute()) {
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
				return false;
			}
		}

		return $db_user->get_db()->affected_rows > 0;
	}