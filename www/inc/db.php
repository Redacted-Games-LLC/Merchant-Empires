<?php 
/**
 * The most useless database class I have ever written.
 *
 * Seriously look at this class. Now look at the usage of this class in the
 * code. Now look back at this class. Now back at the implementation. There
 * is no amount of deodorant to cover the smell.
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
	
	if (!file_exists('DBCONFIG.php')) {
		die('FATAL: including db.php without editing DBCONFIG.php so someone did not follow README.txt');
	}

	include_once('DBCONFIG.php');

	class DB {
		
		private $db = null;
		
		function __construct($user = false) {

			$db = null;
			
			// Un-comment for SSL connection to DB
			//$db = mysqli_init();
			//if (!$db) {
			//	die("mysqli_init failed");
			//}
			//$db->ssl_set(SQL_SSL_CLIENT_KEY, SQL_SSL_CLIENT_CERT, SQL_SSL_CA_CERT, NULL, NULL);

			if ($user) {
				$db = @(new MySQLi(USER_DB_HOST, USER_DB_USER, USER_DB_PASS, USER_DB_NAME));
				// Comment line above and un-comment lines below for SSL connection to DB
				//if(!$db->real_connect(USER_DB_HOST, USER_DB_USER, USER_DB_PASS, USER_DB_NAME, USER_DB_PORT, NULL, MYSQLI_CLIENT_SSL)) {
				//	error_log(__FILE__ . '::' . __LINE__ . ' User database connect error: (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
				//	header('Location: login.php?rc=1165');
				//	die();
				//}
			}
			else {
				$db = @(new MySQLi(DB_HOST, DB_USER, DB_PASS, DB_NAME));
				// Comment line above and un-comment lines below for SSL connection to DB
				//if(!$db->real_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT, NULL, MYSQLI_CLIENT_SSL)) {
				//	error_log(__FILE__ . '::' . __LINE__ . ' Game database connect error: (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
				//	header('Location: login.php?rc=1165');
				//	die();
				//}
			}
			
			if ($db->connect_errno) {
				error_log(__FILE__ . '::' . __LINE__ . 'Database connect error: ' . $db->connect_error);
				header('Location: login.php?rc=1165');
				die();
			}
			
			$this->db = $db;
		}
		
		function __destruct() {
			if (!is_null($this->db)) {
				$this->db->close();
				$this->db = null;
			}
		}
		
		public function get_db() {
			return $this->db;
		}
		
		public function prepare($statement) {
			$stmt = $this->db->prepare($statement);
		}
		
		public function last_insert_id($table) {

			if (!preg_match('/^[_a-zA-Z0-9]{1,20}$/', $table)) {
				error_log(__FILE__ . '::' . __LINE__ . " Invalid table name provided: " . $table);
				return 0;
			}

			$rs = $this->db->query("select last_insert_id() as id from `$table` limit 1");

			if (!$rs) {
				error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $this->db->errno . ") " . $this->db->error);
				return 0;
			}

			$rs->data_seek(0);

			if ($row = $rs->fetch_assoc()) {
				return $row['id'];
			}

			return 0;
		}

		public function found_rows() {

			$rs = $this->db->query("select found_rows() as count");

			if (!$rs) {
				error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $this->db->errno . ") " . $this->db->error);
				return 0;
			}

			$rs->data_seek(0);

			if ($row = $rs->fetch_assoc()) {
				return $row['count'];
			}

			return 0;
		}		
	}
