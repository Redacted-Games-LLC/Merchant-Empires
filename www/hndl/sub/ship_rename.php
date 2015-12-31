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

	include_once('hndl/common.php');

	do {
		$db = isset($db) ? $db : new DB;
		$player_id = PLAYER_ID;


		if (!isset($_REQUEST['ship_name']) || !isset($_REQUEST['ship_style'])) {
			$return_codes[] = 1068;
			break;
		}

		$ship_name = $_REQUEST['ship_name'];
		$ship_style = $_REQUEST['ship_style'];

		if (strlen($ship_name) <= 0) {

			// Delete ship name and style.

			if (!($st = $db->get_db()->prepare("update players set ship_name = '' where record_id = ?"))) {
				error_log("Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
				break;
			}
			
			$st->bind_param("i", $player_id);
			
			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log("Query execution failed: (" . $st->errno . ") " . $st->error);
				break;
			}

			$return_codes[] = 1049;
			break;
		}

		if (preg_match('/^[_a-zA-Z0-9]{1,12}$/', $ship_name) <= 0) {
			$return_codes[] = 1067;
			break;
		}

		if (strlen($ship_style) > 0) {

			if (preg_match('/^[#;a-zA-Z0-9]{1,80}$/', $ship_style) <= 0) {
				$return_codes[] = 1060;
				break;
			}

			$parts = explode(';', $ship_style);

		}

		// Update record

		if (!($st = $db->get_db()->prepare("update players set ship_name = ? where record_id = ?"))) {
			error_log("Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		

		$st->bind_param("si", $ship_name, $player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log("Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		$return_codes[] = 1065;
		break;


	} while (false);


?>