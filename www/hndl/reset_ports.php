<?php
/**
 * Clears all ports from the game and regenerates them.
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

	if (USER_ID <= 0) {
		header('Location: login.php?rc=1030');
		die();
	}
	
	if (!get_user_field(USER_ID, 'admin', 'system')) {
		header('Location: viewport.php?rc=1030');
		die();
	}

	if (isset($_SESSION['form_id'])) {
		if (!isset($_REQUEST['form_id']) || $_SESSION['form_id'] != $_REQUEST['form_id']) {
			header('Location: viewport.php?rc=1181');
			die();
		}
	}

	do { // Dummy loop
	
		$return_page = 'admin';
		$return_vars['page'] = 'system';

		$db = isset($db) ? $db : new DB;

		// Start by deleting existing ports

		if (!($st = $db->get_db()->prepare("delete from port_goods where record_id > 0"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		if (!($st = $db->get_db()->prepare("delete from places where type = (select record_id from place_types where caption = 'Port')"))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		// Now find out where we can put new ports down

		$port_place_types = array();
		$place_types = array();
		$place_type_count = 0;

		include_once('inc/place_types.php');

		foreach ($spacegame['place_types'] as $type => $row) {
			if ($row['port_goods'] > 0) {
				$port_place_types[$type] = $row['port_goods'];
				$place_types[] = $type;
				$place_type_count++;
			}
		}

		if ($place_type_count <= 0) {
			$return_codes[] = 1056;
			break;
		}

		$port_places = array();
		$port_place_count = 0;

		$place_type_string = join(',', $place_types);

		$rs = $db->get_db()->query("select places.*, systems.protected from places, systems where type in ($place_type_string) and systems.record_id = places.system and protected = 1");

		if (!$rs || !$rs->data_seek(0)) {
			$return_codes[] = 1057;
			error_log(__FILE__ . '::' . __LINE__ . " Query selection failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}

		while ($row = $rs->fetch_assoc()) {

			if (isset($port_place_types[$row['type']])) {
				$port_places[$row['record_id']] = $row;
				$port_place_count++;
			}
		}

		if ($port_place_count <= 0) {
			$return_codes[] = 1057;
			break;
		}

		// Now find out what goods we can add to a port
		include_once('inc/start_goods.php');

		if ($spacegame['start_good_count'] <= 0) {
			$return_codes[] = 1058;
			break;
		}

		$demand_start_amount = -PORT_LIMIT;
		$supply_start_amount = PORT_LIMIT;

		include_once('inc/galaxy.php');

		// Add each port and goods to the database.

		foreach ($port_places as $id => $row) {
			if (!insert_port($row['caption'], $row['system'], $row['x'], $row['y'], $port_place_types[$row['type']] + PLACE_TYPE_SUPPLY_OFFSET, $port_place_types[$row['type']] + PLACE_TYPE_DEMAND_OFFSET, $row['type'], false)) {
				$return_codes[] = 1074;
				break 2;
			}
		}
		
		update_distances();

	} while (false);




?>