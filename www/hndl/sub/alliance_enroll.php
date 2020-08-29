<?php
/**
 * Handles enrolling a member into an alliance
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
		
		if (!defined('ALLIANCE_LEADER') || !ALLIANCE_LEADER) {
			$return_codes[] = 1086;
			break;
		}

		if (!isset($_REQUEST['player_id']) || !is_numeric($_REQUEST['player_id']) || $_REQUEST['player_id'] < 0) {
			$return_codes[] = 1087;
			break;
		}

		$other_player_id = $_REQUEST['player_id'];
		$other_player = array();

		if ($other_player_id == $spacegame['player']['record_id']) {
			$return_codes[] = 1014;
			break;
		}

		$time = PAGE_START_TIME;

		$db = isset($db) ? $db : new DB;

		$rs = $db->get_db()->query("select * from players where record_id = '". $other_player_id ."' limit 1");

		$rs->data_seek(0);
		if ($row = $rs->fetch_assoc()) {
			$other_player = $row;
		}
		else {
			$return_codes[] = 1014;
			break;
		}

		if ($other_player['alliance'] > 0) {
			$return_codes[] = 1094;
			break;
		}

		// Verify the invitation exists

		$rs = $db->get_db()->query("select * from alliance_invitations where player = '". $other_player_id ."' and alliance = '" . $spacegame['player']['alliance'] . "' limit 1");

		$rs->data_seek(0);
		if (!($row = $rs->fetch_assoc())) {
			$return_codes[] = 1095;
			break;
		}

		// Update the alliance tax rate

		$tax_mult = 1.01;

		$rs = $db->get_db()->query("select count(*) as count from players where alliance = '". $spacegame['player']['alliance'] ."'");

		$rs->data_seek(0);
		if ($row = $rs->fetch_assoc()) {
			$tax_mult = (1.0 + ceil(100 * (ALLIANCE_START_TAX + ($row['count'] * ALLIANCE_MEMBER_TAX))) / 100);
		}

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

		// Remove invitations from the player

		if (!($st = $db->get_db()->prepare('delete from alliance_invitations where player = ?'))) {
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

		// Enroll the player

		if (!($st = $db->get_db()->prepare('update players set alliance = ? where record_id = ?'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("ii", $spacegame['player']['alliance'], $other_player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		// Update bases and ordnance

		if (!($st = $db->get_db()->prepare('update bases set alliance = ? where owner = ?'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("ii", $spacegame['player']['alliance'], $other_player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		if (!($st = $db->get_db()->prepare('update ordnance set alliance = ? where owner = ?'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("ii", $spacegame['player']['alliance'], $other_player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		$return_codes[] = 1096;

	} while (false);