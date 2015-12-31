<?php
/**
 * Handles deploying a base package to create new bases on a planet
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

	do { // Dummy Loop

		// $db, $tech, and $good should be set by ship_deploy.php which calls this file.

		if ($spacegame['player']['turns'] < DEPLOY_TURN_COST) {
			$return_codes[] = 1018;
			break;
		}

		$db = isset($db) ? $db : new DB;

		// Remove some turns

		$player_id = PLAYER_ID;
		$turn_cost = DEPLOY_TURN_COST;
		
		if (!($st = $db->get_db()->prepare('update players set turns = turns - ? where record_id = ?'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("ii", $turn_cost, $player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		// Find out if we are over a place which supports bases

		include_once('inc/places.php');
		include_once('inc/galaxy.php');

		$over_a_base_carrier = false;
		$bases_already_here = 0;
		
		foreach ($spacegame['places'] as $place_id => $place) {
			
			if ($place['type'] == 'Base') {
				$base_already_here++;
			}
			elseif ($spacegame['place_types'][$place['place_type']]['deploy_bases'] > 0) {
				$over_a_base_carrier = true;
			}

		}

		if ($base_already_here >= MAX_BASES_PER_PLANET) {
			$return_codes[] = 1108;
			break;
		}

		if (!$over_a_base_carrier) {
			$return_codes[] = 1107;
			break;
		}

		// Make sure we aren't in a protected system

		include_once('inc/systems.php');

		if (!isset($spacegame['system'])) {
			$return_codes[] = 1101;
			break;
		}

		if ($spacegame['system']['protected']) {
			$return_codes[] = 1105;
			break;
		}


		// Make sure player isn't at their own limit
		$base_limit = START_BASE_COUNT + floor($spacegame['player']['level'] / LEVELS_PER_EXTRA_BASE);

		$base_count = 0;

		$rs = $db->get_db()->query("select * from bases where owner = '$player_id'");
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$base_count++;
		}

		if ($base_count >= $base_limit) {
			$return_codes[] = 1109;
			break;
		}



		// Remove a cargo entry
		
		if (!($st = $db->get_db()->prepare("update player_cargo set amount = amount - 1 where record_id = ? and amount = ?"))) {
			error_log("Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("ii", $cargo_id, $tech['amount']);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log("Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}

		$caption = '';

		if (!isset($_REQUEST['caption']) || strlen($_REQUEST['caption']) <= 0) {
			$caption = DEFAULT_BASE_CAPTION;
		}
		else {
			if (trim($_REQUEST['caption']) != $_REQUEST['caption']) {
				$return_codes[] = 1111;
				break;
			}

			if (str_replace('  ', ' ', $_REQUEST['caption']) != $_REQUEST['caption']) {
				$return_codes[] = 1111;
				break;
			}

			if (!preg_match('/^[a-zA-Z0-9-_\'" ]{1,24}$/i', $_REQUEST['caption'])) {
				$return_codes[] = 1110;
				break;
			}

			$caption = $_REQUEST['caption'];
		}



		// Alright, let us deploy the base.

		if (!insert_base($caption, $spacegame['system']['record_id'], $spacegame['player']['x'], $spacegame['player']['y'], $spacegame['player']['record_id'], $spacegame['player']['alliance'])) {
			$return_codes[] = 1074;
			break;
		}

		$return_codes[] = 1112;

	} while (false);

?>