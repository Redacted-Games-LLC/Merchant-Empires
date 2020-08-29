<?php
/**
 * Handles creating a new player for a user.
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

	define('CLOSE_SESSION', false);
	include_once('inc/page.php');

	if (isset($_SESSION['form_id'])) {
		if (!isset($_REQUEST['form_id']) || $_SESSION['form_id'] != $_REQUEST['form_id']) {
			header('Location: viewport.php?rc=1181');
			die();
		}
	}

	$return_page = 'select_player';

	do { /* Dummy loop for "break" support. */
		
		$player_name = $_POST['player_name'];
		$player_race = $_POST['player_race'];
		
		if (!validate_playername($player_name)) {
			// TODO: Log possible cheat attempt
			$return_codes[] = 1011;
			break;
		}
		
		if ($player_race < 0 || !is_numeric($player_race)) {
			error_log('User ' . USER_ID . ' from ' . getClientIP() . ' attempted to access an invalid race.');
			$return_codes[] = 1009;
			break;
		}

		$race_list = array();
		$race_count = 0;
		
		$db = isset($db) ? $db : new DB;
		
		$rs = $db->get_db()->query("select record_id, caption from races order by record_id");
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$race_list[$row['record_id']] = $row['caption'];
			$race_count++;
		}
		
		if ($player_race == 0) {
			for ($i = 0; $i < 10000; $i++) {
				$player_race = 1 + (mt_rand(0,99999) % $race_count);
			}
		}
		
		if (!isset($race_list[$player_race])) {
			error_log('User ' . USER_ID . ' from ' . getClientIP() . ' attempted to access an invalid race.');
			$return_codes[] = 1009;
			break;
		}
		
		$rs = $db->get_db()->query("select record_id as id from players where lower(caption) = '". strtolower($player_name) ."' limit 1");
		
		$rs->data_seek(0);
		if ($row = $rs->fetch_assoc()) {
			$return_codes[] = 1012;
			break;
		}
		
		$rs = $db->get_db()->query("select count(*) as player_count from user_players where user = '". (USER_ID * 1) ."'");
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			if ($row['player_count'] >= MAX_PLAYERS_PER_USER) {
				$return_codes[] = 1013;
				break 2;
			}
		}
		
		$starts = array();
		$start_count = 0;

		$rs = $db->get_db()->query("select places.x, places.y from places, place_types, systems where places.type = place_types.record_id and places.system = systems.record_id and place_types.caption = 'Ship Dealer' and systems.race = " . $player_race);
		
		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$starts[] = array('x' => $row['x'], 'y' => $row['y']);
			$start_count += 1;
		}
		
		$start_turns = START_TURNS;
		$start_credits = round(START_CREDITS);

		$start_x = 500;
		$start_y = 500;
		
		if ($start_count > 0) {
			$start = $starts[(mt_rand(0,99999) % $start_count)];
			$start_x = $start['x'] + mt_rand(-MAX_START_DISTANCE, MAX_START_DISTANCE);
			$start_y = $start['y'] + mt_rand(-MAX_START_DISTANCE, MAX_START_DISTANCE);
		}
		else {
			$start_x -= 50 + mt_rand(0, 100);
			$start_y -= 50 + mt_rand(0, 100);
		}

		$db->get_db()->autocommit(false);

		if (!($st = $db->get_db()->prepare('INSERT INTO players (caption, race, turns, credits, x, y) VALUES (?,?,?,?,?,?)'))) {
			$db->get_db()->rollback();
			$db->get_db()->autocommit(true);
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Insert Player prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}
		
		if (!$st->bind_param("siidii", $player_name, $player_race, $start_turns, $start_credits, $start_x, $start_y)) {
			$db->get_db()->rollback();
			$db->get_db()->autocommit(true);
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Binding of player fields failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		if (!$st->execute()) {
			$db->get_db()->rollback();
			$db->get_db()->autocommit(true);
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Insert player query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		$player_id = $db->last_insert_id('players');

		if ($player_id <= 0) {
			$db->get_db()->rollback();
			$db->get_db()->autocommit(true);
			$return_codes[] = '1006';
			error_log(__FILE__ . '::' . __LINE__ . " Failed to get last insert id after successful player creation. (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}

		if (!($st = $db->get_db()->prepare('INSERT INTO user_players (user, player, session_id, session_time) VALUES (?,?,?,?)'))) {
			$db->get_db()->rollback();
			$db->get_db()->autocommit(true);
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$user_id = USER_ID;
		$session_id = session_id();
		$time = PAGE_START_TIME;
		
		if (!$st->bind_param("iisi", $user_id, $player_id, $session_id, $time)) {
			$db->get_db()->rollback();
			$db->get_db()->autocommit(true);
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Binding of USER/PLAYER data failed (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}

		if (!$st->execute()) {
			$db->get_db()->rollback();
			$db->get_db()->autocommit(true);
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			break;
		}
		
		$id = $db->last_insert_id('user_players');
		
		if ($id <= 0) {
			$db->get_db()->rollback();
			$db->get_db()->autocommit(true);
			$return_codes[] = '1006';
			error_log(__FILE__ . '::' . __LINE__ . " Failed to get last insert id after successful user/player link. (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			// TODO: Better way to deal with this situation.
			break;
		}

		$db->get_db()->commit();
		$db->get_db()->autocommit(true);

		$_SESSION['pid'] = $player_id;

	} while (false);

	session_write_close();