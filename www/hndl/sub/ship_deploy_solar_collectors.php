<?php
/**
 * Handles deploying solar collectors on a star.
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
	include_once('inc/galaxy.php');

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

		// Find out if we are over a place which supports solar collectors

		$over_a_star = false;
		$collector_count = 0;
		$star = array();

		foreach ($spacegame['places'] as $place_id => $place) {
			if ($place['type'] == 'Solar Collector') {
				$collector_count++;
				continue;
			}

			if ($spacegame['place_types'][$place['place_type']]['deploy_solar_collectors'] > 0) {
				$over_a_star = true;
				$star = $place;
			}
		}

		if ($collector_count >= SOLAR_COLLECTORS_PER_SECTOR) {
			$return_codes[] = 1077;
			break;
		}

		if (!$over_a_star) {
			$return_codes[] = 1078;
			break;
		}


		// Remove a cargo entry
		
		if (!($st = $db->get_db()->prepare("update player_cargo set amount = amount - 1 where record_id = ? and amount = ?"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("ii", $cargo_id, $tech['amount']);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}


		// Alright, let us deploy the solar collector.

		if (!insert_solar_collector($star['caption'] . ' Energy', $star['system'], $star['x'], $star['y'])) {
			$return_codes[] = 1074;
			break;
		}

		$return_codes[] = 1079;

	} while (false);

?>