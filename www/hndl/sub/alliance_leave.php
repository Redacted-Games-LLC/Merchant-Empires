<?php
/**
 * Handles removing a member from an alliance
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
	include_once('inc/alliance.php');

	$return_vars['page'] = 'main';
	
	do { // Dummy Loop
		
		$alliance_id = $spacegame['player']['alliance'];

		if ($alliance_id <= 0) {
			$return_codes[] = 1085;
			break;
		}
		
		if (!isset($_REQUEST['player_id']) || !is_numeric($_REQUEST['player_id']) || $_REQUEST['player_id'] < 0) {
			$return_codes[] = 1087;
			break;
		}

		$other_player_id = $_REQUEST['player_id'];
		$other_player = array();

		$time = PAGE_START_TIME;
		$db = isset($db) ? $db : new DB;


		if ($other_player_id == $spacegame['player']['record_id']) {
			// Player is leaving
			$other_player = $spacegame['player'];
		}
		else if (!defined('ALLIANCE_LEADER') || !ALLIANCE_LEADER) {
			$return_codes[] = 1086;
			break;
		}
		else {
			// Player may be getting kicked
			$return_vars['page'] = 'members';

			$rs = $db->get_db()->query("select * from players where record_id = '". $other_player_id ."' limit 1");

			$rs->data_seek(0);
			if ($row = $rs->fetch_assoc()) {
				$other_player = $row;
			}
			else {
				$return_codes[] = 1014;
				break;
			}
		}

		if ($other_player['alliance'] != $spacegame['player']['alliance']) {
			$return_codes[] = 1097;
			break;
		}

		if ($spacegame['alliance']['founder'] == $other_player_id) {
			$return_codes[] = 1098;
			break;
		}

		// Remove the player

		if (!($st = $db->get_db()->prepare('update players set alliance = null where record_id = ?'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("i", $other_player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		if (!($st = $db->get_db()->prepare('update bases set alliance = null where owner = ?'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("i", $other_player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		if (!($st = $db->get_db()->prepare('update ordnance set alliance = null where owner = ?'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("i", $other_player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		// Update the alliance tax rate

		$tax_mult = 1.01;

		$rs = $db->get_db()->query("select count(*) as count from players where alliance = '". $spacegame['player']['alliance'] ."'");

		$rs->data_seek(0);
		if ($row = $rs->fetch_assoc()) {

			$tax_mult = (1.0 + ceil(100 * (ALLIANCE_START_TAX + (($row['count'] - 1) * ALLIANCE_MEMBER_TAX))) / 100);

			if ($row['count'] > 0) {
				if (!($st = $db->get_db()->prepare('update alliances set tax_mult = ? where record_id = ?'))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param("di", $tax_mult, $spacegame['player']['alliance']);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					break;
				}
			}
			else {
				// Remove the alliance

				if (!($st = $db->get_db()->prepare('delete from alliances where record_id = ?'))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param("i", $spacegame['player']['alliance']);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					break;
				}
			}
		}

		$return_codes[] = 1099;

	} while (false);