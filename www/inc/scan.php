<?php
/**
 * Loads scanner information for the popup
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

	include_once('inc/game.php');
	

	do { // Dummy loop
		
		$player_id = PLAYER_ID;
		$turn_cost = SCAN_TURN_COST;

		if ($spacegame['player']['turns'] < $turn_cost) {
			header('Location: error.php?rc=1018');
			die();
		}

		$db = isset($db) ? $db : new DB;

		if (!($st = $db->get_db()->prepare("update players set turns = turns - ? where record_id = ?"))) {
			$db->get_db()->rollback();
			$db->get_db()->autocommit(true);
			error_log(__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			header('Location: error.php?rc=1006');
			die();
		}
		
		$st->bind_param("ii", $turn_cost, $player_id);
		
		if (!$st->execute()) {
			$db->get_db()->rollback();
			$db->get_db()->autocommit(true);
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			header('Location: error.php?rc=1006');
			die();
		}

		// grab scan x and y

		if (!isset($_REQUEST['x']) || !is_numeric($_REQUEST['x']) || !isset($_REQUEST['y']) || !is_numeric($_REQUEST['y'])) {
			header('Location: error.php?rc=1106');
			die();
		}
		
		$scan_x = $_REQUEST['x'];
		$scan_y = $_REQUEST['y'];

		// Make sure it is nearby

		$dx = $scan_x - $spacegame['player']['x'];
		$dy = $scan_y - $spacegame['player']['y'];

		if ($dx < -1 || $dx > 1 || $dy < -1 || $dy > 1) {
			header('Location: error.php?rc=1017');
			die();
		}

		// Grab the details

		$spacegame['scan'] = array();
		$spacegame['scan_count'] = 0;

		$rs = $db->get_db()->query("select ordnance.*, players.caption from ordnance, players where ordnance.owner = players.record_id and ordnance.x = '{$scan_x}' and ordnance.y = '{$scan_y}' order by ordnance.amount desc, ordnance.record_id");
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$spacegame['scan'][$row['record_id']] = $row;
			$spacegame['scan_count']++;
		}

	} while (false);

?>