<?php
/**
 * Handles rejecting a potential recruit from an alliance.
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

	$return_vars['page'] = 'main';
	
	do { // Dummy Loop
		$alliance_id = $spacegame['player']['alliance'];

		if ($alliance_id <= 0) {
			$return_codes[] = 1085;
			break;
		}
		
		if (!ALLIANCE_LEADER) {
			$return_codes[] = 1086;
			break;
		}

		if (!isset($_REQUEST['player_id']) || !is_numeric($_REQUEST['player_id']) || $_REQUEST['player_id'] < 0) {
			$return_codes[] = 1087;
			break;
		}

		$player_id = $_REQUEST['player_id'];
		$time = PAGE_START_TIME;

		$db = isset($db) ? $db : new DB;

		if (!($st = $db->get_db()->prepare('update alliance_invitations set rejected = ? where alliance = ? and player = ? and rejected <= 0'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("iii", $time, $alliance_id, $player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		$return_codes[] = 1093;

	} while (false);