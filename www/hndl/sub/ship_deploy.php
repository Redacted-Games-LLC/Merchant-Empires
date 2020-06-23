<?php
/**
 * Calls various deployment files 
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
	include_once('inc/goods.php');
	include_once('inc/cargo.php');

	do { // Dummy Loop

		$db = isset($db) ? $db : new DB;
		$player_id = PLAYER_ID;

		// NOTE that cargo id is not the good id but the record id of the specific cargo table record.
		if (!isset($_REQUEST['cargo_id']) || !is_numeric($_REQUEST['cargo_id']) || !isset($spacegame['tech'][$_REQUEST['cargo_id']])) {
			$return_codes[] = 1021;
			break;
		}

		$cargo_id = $_REQUEST['cargo_id'];
		$tech = $spacegame['tech'][$cargo_id];
		$good = $spacegame['goods'][$spacegame['tech'][$cargo_id]['good']];
		
		if ($tech['amount'] <= 0) {
			$return_codes[] = 1075;
			break;
		}

		
		include_once('inc/places.php');
		include_once('inc/place_types.php');

		
		switch ($good['safe_caption']) {
			case 'port_package':
			case 'base_package':
			case 'shields':
			case 'armor':
			case 'solar_collectors':
			case 'drones':
			case 'mines':

				$deploy_page = $good['safe_caption'];
				$deploy_file = "hndl/sub/ship_deploy_{$deploy_page}.php";

				if (!file_exists($deploy_file)) {
					$return_codes[] = 1041;
					error_log(__FILE__ . '::' . __LINE__ . ' Valid deploy caption does not have an include.');
					break;
				}
				
				include_once($deploy_file);
				break;

			default:
				$return_codes[] = 1041;
				break;
		}

	} while (false);


?>