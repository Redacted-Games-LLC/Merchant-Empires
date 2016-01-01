<?php
/**
 * Handles adding gold keys to the database
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
	include_once('inc/game.php');

	$return_page = 'admin';
	$return_vars['page'] = 'gold';
	
	do { // Dummy Loop
		
		if (!isset($_REQUEST['keys']) || strlen($_REQUEST['keys']) < MINIMUM_KEY_LENGTH) {
			$return_codes[] = 1121;
			break;
		}

		$keys = explode("\n", $_REQUEST['keys']);


		$db = isset($db) ? $db : new DB;

		if (!($st = $db->get_db()->prepare('insert into gold_keys (`type`, `key`, `time`) values (?, ?, ?)'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
			
		$st->bind_param("isi", $type, $key, $time);
		
		foreach ($keys as $key) {

			$key = trim($key);

			if ($key == '') {
				continue;
			}

			if (!validate_key($key)) {
				$return_codes[] = 1121;
				break 2;
			}

			$type_str = substr($key, 9, 4);
			
			switch ($type_str) {
				case 'GIFT': 
					$type = 1;
					break;

				default:
					$type = 0;
					break;
			}
		
			$time_str = substr($key, 14, 3);
			$time = 86400;

			if (is_numeric($time_str)) {
				$time *= $time_str;
			}

			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
				break;
			}
		}


		$return_codes[] = 1122;
		
	} while (false);


?>