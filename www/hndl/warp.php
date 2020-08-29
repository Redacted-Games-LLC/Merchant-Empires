<?php
/**
 * Handles warping from one sector to another
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
	include_once('inc/warp.php');
	include_once('inc/ships.php');

	if (isset($_SESSION['form_id']) && (!isset($_REQUEST['form_id']) || $_SESSION['form_id'] != $_REQUEST['form_id'])) {
		header('Location: viewport.php?rc=1181');
		die();
	}

	$return_page = 'viewport';

	do { // dummy loop
		if (!isset($spacegame['warp'])) {
			$return_codes[] = 1040;
			break;
		}

		$db = isset($db) ? $db : new DB;

		$dx = $spacegame['warp']['x'];
		$dy = $spacegame['warp']['y'];

		$turns = $spacegame['player']['turns'];
		$turn_cost = $spacegame['ship']['tps'] * WARP_TURN_MULTIPLIER;
		
		if ($turn_cost > $turns) {
			$return_codes[] = 1018;
			break;
		}
		
		$time = PAGE_START_TIME;
		$player_id = PLAYER_ID;
		$x = $spacegame['player']['x'];
		$y = $spacegame['player']['y'];

		if (!($st = $db->get_db()->prepare('update players set x = ?, y = ?, turns = turns - ?, target_type = 0, target_x = 0, target_y = 0, last_move = ? where record_id = ? and x = ? and y = ?'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("iiiiiii", $dx, $dy, $turn_cost, $time, $player_id, $x, $y);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}
	} while (false);