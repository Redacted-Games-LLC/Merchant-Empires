<?php
/**
 * Sets a target for navigational purposes.
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

	include_once('inc/page.php');
	include_once('inc/game.php');

	if (isset($_SESSION['form_id'])) {
		if (!isset($_REQUEST['form_id']) || $_SESSION['form_id'] != $_REQUEST['form_id']) {
			header('Location: viewport.php?rc=1181');
			die();
		}
	}
	
	$return_page = 'viewport';

	do { // dummy loop

		$turns = $spacegame['player']['turns'];
		$turn_cost = TARGET_TURN_COST;
		
		if ($turn_cost > $turns) {
			$return_codes[] = 1018;
			break;
		}
		
		$rx = 0;
		$ry = 0;
		$rtype = 0;
		
		if (!isset($_REQUEST['x']) || !is_numeric($_REQUEST['x'])) {
			$return_codes[] = 1052;
			break;
		}

		$rx = $_REQUEST['x'];
		
		if ($rx < 0 || $rx > 999) {
			$return_codes[] = 1016;
			break;
		}

		if (!isset($_REQUEST['y']) || !is_numeric($_REQUEST['y'])) {
			$return_codes[] = 1052;
			break;
		}

		$ry = $_REQUEST['y'];
		
		if ($ry < 0 || $ry > 999) {
			$return_codes[] = 1016;
			break;
		}
		
		if (!isset($_REQUEST['type']) || !is_numeric($_REQUEST['type'])) {
			$return_codes[] = 1059;
			break;
		}

		$rtype = $_REQUEST['type'];
		
		// TODO: Get the type range from elsewhere. Right now it is arbitrary.
		if ($rtype < 1 || $rtype > 5) {
			$return_codes[] = 1059;
			break;
		}
		
		$db = isset($db) ? $db : new DB;

		if (!($st = $db->get_db()->prepare('update players set target_x = ?, target_y = ?, target_type = ?, turns = turns - ? where record_id = ? and turns = ?'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$player_id = PLAYER_ID;
		$st->bind_param("iiiiii", $rx, $ry, $rtype, $turn_cost, $player_id, $turns);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

	} while (false);

?>