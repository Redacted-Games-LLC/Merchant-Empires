<?php
/**
 * Handles administering goods
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

	if (isset($_SESSION['form_id'])) {
		if (!isset($_REQUEST['form_id']) || $_SESSION['form_id'] != $_REQUEST['form_id']) {
			header('Location: viewport.php?rc=1181');
			die();
		}
	}

	if (USER_ID <= 0) {
		header('Location: login.php?rc=1030');
		die();
	}
	
	if (!get_user_field(USER_ID, 'admin', 'goods')) {
		header('Location: viewport.php?rc=1030');
		die();
	}

	do { // Dummy loop
	
		$return_page = 'admin';
		$return_vars['page'] = 'goods';

		$good = array();
		$good_id = 0;

		$request_name = (isset($_REQUEST['name']) ? $_REQUEST['name'] : null);
		$request_level = (isset($_REQUEST['level']) ? $_REQUEST['level'] : null);
		$request_tech = (isset($_REQUEST['tech']) ? $_REQUEST['tech'] : null);
		$request_requirement = (isset($_REQUEST['requirement']) ? $_REQUEST['requirement'] : null);
		$request_target = (isset($_REQUEST['target']) ? $_REQUEST['target'] : null);
		$request_percent = (isset($_REQUEST['percent']) ? $_REQUEST['percent'] : null);
		$request_supply = (isset($_REQUEST['supply']) ? $_REQUEST['supply'] : null);
		$request_start = (isset($_REQUEST['start']) ? $_REQUEST['start'] : null);
		
		include_once('inc/goods.php');

		if (isset($_REQUEST['id']) ? $_REQUEST['id'] : null) {
			$good_id = $_REQUEST['id'];
			$good = $spacegame['goods'][$good_id];
			$return_vars['page'] = 'good';
			$return_vars['id'] = $good_id;
		}
		
		if (!isset($_REQUEST['subtask'])) {
			$return_codes[] = 1041;
		}		

		switch ($_REQUEST['subtask']) {

			case 'create':
				
				$return_vars['page'] = 'goods';

				if (!isset($request_name) || preg_match('/^[ a-zA-Z0-9]{1,24}$/', $request_name) <= 0) {
					$return_codes[] = 1042;
					break 2;
				}

				if (!isset($request_level) || !is_numeric($request_level) || $request_level < 1 || $request_level > 15) {
					$return_codes[] = 1043;
					break 2;
				}

				$db = isset($db) ? $db : new DB;

				$rs = $db->get_db()->query("select * from goods where lower(caption) = '" . strtolower($request_name) . "' limit 1");
		
				$rs->data_seek(0);
				
				if ($row = $rs->fetch_assoc()) {
					$return_codes[] = 1044;
					break 2;
				}

				if (!($st = $db->get_db()->prepare('insert into goods (caption, level) values (?,?)'))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param("si", $request_name, $request_level);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					break;
				}

				$return_codes[] = 1045;
				break;

			case 'update':

				$return_vars['page'] = 'good';

				if ($good_id <= 0) {
					$return_codes[] = 1021;
					break 2;
				}

				if (!isset($request_name) || preg_match('/^[ a-zA-Z0-9]{1,24}$/', $request_name) <= 0) {
					$return_codes[] = 1042;
					break 2;
				}

				if (!isset($request_level) || !is_numeric($request_level) || $request_level < 1 || $request_level > 15) {
					$return_codes[] = 1043;
					break 2;
				}

				if (!isset($request_tech) || !is_numeric($request_tech) || $request_tech < 0) {
					$return_codes[] = 1069;
					break 2;
				}

				if (strtolower($good['caption']) == strtolower($request_name) && $good['level'] == $request_level && $good['tech'] == $request_tech) {
					$return_codes[] = 1046;
					break 2;
				}

				$db = isset($db) ? $db : new DB;

				$rs = $db->get_db()->query("select * from goods where lower(caption) = '" . strtolower($request_name) . "' and record_id <> '". $good_id ."' limit 1");
		
				$rs->data_seek(0);
				
				if ($row = $rs->fetch_assoc()) {
					$return_codes[] = 1044;
					break 2;
				}

				if (!($st = $db->get_db()->prepare('update goods set caption = ?, level = ?, tech = ? where record_id = ?'))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param("siii", $request_name, $request_level, $request_tech, $good_id);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					break;
				}

				$return_codes[] = 1047;
				break;

			case 'add_requirement':

				$return_vars['page'] = 'good';

				if ($good_id <= 0) {
					$return_codes[] = 1021;
					break 2;
				}

				if (!isset($request_requirement) || !is_numeric($request_requirement)) {
					$return_codes[] = 1021;
					break 2;
				}

				$db = isset($db) ? $db : new DB;

				$rs = $db->get_db()->query("select * from good_upgrades where good = '".$request_requirement."' and target='".$good_id."'");
		
				$rs->data_seek(0);
				
				if ($row = $rs->fetch_assoc()) {
					$return_codes[] = 1048;
					break 2;
				}

				if (!($st = $db->get_db()->prepare("insert into good_upgrades (good, target) values (?,?)"))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param("ii", $request_requirement, $good_id);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					break;
				}

				$return_codes[] = 1049;
				break;

			case 'delete_requirement':
				
				$return_vars['page'] = 'good';

				if ($good_id <= 0) {
					$return_codes[] = 1021;
					break 2;
				}

				if (!isset($request_requirement) || !is_numeric($request_requirement)) {
					$return_codes[] = 1021;
					break 2;
				}

				$db = isset($db) ? $db : new DB;

				$rs = $db->get_db()->query("select * from good_upgrades where good = '".$request_requirement."' and target='".$good_id."'");
		
				$rs->data_seek(0);
				
				if (!($row = $rs->fetch_assoc())) {
					$return_codes[] = 1051;
					break 2;
				}

				if (!($st = $db->get_db()->prepare("delete from good_upgrades where good = ? and target = ?"))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param("ii", $request_requirement, $good_id);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					break;
				}

				$return_codes[] = 1055;
				break;

			case 'add_start':

				$return_vars['page'] = 'good';

				if ($good_id <= 0) {
					$return_codes[] = 1021;
					break 2;
				}

				if (!isset($request_target) || !is_numeric($request_target)) {
					$return_codes[] = 1021;
					break 2;
				}

				if (!isset($request_percent) || !is_numeric($request_percent) || $request_percent < 0 || $request_percent > 100) {
					$return_codes[] = 1053;
					break 2;
				}

				if (!isset($request_supply) || !is_numeric($request_supply) || $request_supply < 0 || $request_supply > 1) {
					$return_codes[] = 1054;
					break 2;
				}

				$db = isset($db) ? $db : new DB;

				$rs = $db->get_db()->query("select * from start_goods where good = '". $good_id ."' and place_type = '".$request_target."' and supply = '".$request_supply."'");
		
				$rs->data_seek(0);
				
				if ($row = $rs->fetch_assoc()) {
					$return_codes[] = 1048;
					break 2;
				}

				if (!($st = $db->get_db()->prepare("insert into start_goods (good, place_type, percent, supply) values (?,?,?,?)"))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param("iiii", $good_id, $request_target, $request_percent, $request_supply);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					break;
				}

				$return_codes[] = 1049;
				break;

			case 'delete_start':
				
				$return_vars['page'] = 'good';

				if ($good_id <= 0) {
					$return_codes[] = 1021;
					break 2;
				}

				if (!isset($request_start) || !is_numeric($request_start)) {
					$return_codes[] = 1021;
					break 2;
				}

				$db = isset($db) ? $db : new DB;

				if (!($st = $db->get_db()->prepare("delete from start_goods where record_id = ?"))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param("i", $request_start);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					break;
				}

				$return_codes[] = 1055;
				break;

			case 'delete':

				$return_vars['page'] = 'goods';
				$return_vars['id'] = '0';

				if ($good_id <= 0) {
					$return_codes[] = 1021;
					break 2;
				}

				$db = isset($db) ? $db : new DB;

				if (!($st = $db->get_db()->prepare("delete from dealer_inventory where item_type = (select record_id from item_types where caption = 'Goods') and item = ?"))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param("i", $good_id);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					break;
				}

				if (!($st = $db->get_db()->prepare("delete from port_goods where good = ? or upgrade = ?"))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param("ii", $good_id, $good_id);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					break;
				}

				if (!($st = $db->get_db()->prepare("delete from good_upgrades where good = ? or target = ?"))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param("ii", $good_id, $good_id);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					break;
				}

				if (!($st = $db->get_db()->prepare("delete from start_goods where good = ?"))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param("i", $good_id);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					break;
				}

				if (!($st = $db->get_db()->prepare("delete from player_cargo where good = ?"))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param("i", $good_id);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					break;
				}

				// Last one...

				if (!($st = $db->get_db()->prepare("delete from goods where record_id = ?"))) {
					error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
					$return_codes[] = 1006;
					break;
				}
				
				$st->bind_param("i", $good_id);
				
				if (!$st->execute()) {
					$return_codes[] = 1006;
					error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
					break;
				}

				$return_codes[] = 1055;
				break;

			default:
				$return_codes[] = 1041;
				break 2;
		}
	} while (false);