<?php
/**
 * Handles creating an alliance and enrolling its founder
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
	include_once('inc/alliance.php');

	$return_vars['page'] = 'create';

	do { // Dummy Loop

		if ($spacegame['player']['alliance'] > 0) {
			$return_codes[] = 1082;
			break;
		}

		if (!isset($_REQUEST['name'])) {
			$return_codes[] = 1080;
			break;
		}

		$name = $_REQUEST['name'];

		if (!preg_match('/^[a-zA-Z0-9 ]{2,24}$/', $name)) {
			$return_codes[] = 1080;
			break;
		}

		if (trim(str_replace('  ', ' ', $name)) != $name) {
			$return_codes[] = 1081;
			break;
		}

		$db = isset($db) ? $db : new DB;
		$player_id = PLAYER_ID;
		$tax_mult = 1.0 + (ceil(100 * ALLIANCE_START_TAX + ALLIANCE_MEMBER_TAX) / 100);

		$rs = $db->get_db()->query("select * from alliances where lower(caption) = '". strtolower($name) ."'");

		$rs->data_seek(0);

		if ($row = $rs->fetch_assoc()) {
			$return_codes[] = 1083;
			break;
		}

		if (!($st = $db->get_db()->prepare('insert into alliances (caption, tax_mult, founder) values (?,?,?)'))) {
			error_log("Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("sdi", $name, $tax_mult, $player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log("Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		$alliance_id = $db->last_insert_id('alliances');

		if (!($st = $db->get_db()->prepare('update players set alliance = ? where record_id = ? and alliance <> ?'))) {
			error_log("Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("iii", $alliance_id, $player_id, $alliance_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log("Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}


	} while (false);


?>