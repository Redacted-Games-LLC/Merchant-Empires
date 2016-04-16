<?php
/**
 * Handles creating an end of round gold key for players with remaining gold
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

	$return_page = 'gold';
	
	do { // Dummy Loop

		if (!HAVOC_ROUND) {
			$return_codes[] = 1132;
			break;
		}

		$exp = $spacegame['player']['gold_expiration'];

		if ($exp <= END_OF_ROUND) {
			$return_codes[] = 1133;
			break;
		}

		$player_id = PLAYER_ID;

		$db_user = isset($db_user) ? $db_user : new DB(true);
		$db = isset($db) ? $db : new DB;

		if (!($st = $db->get_db()->prepare('update players set gold_expiration = 0 where record_id = ?'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("i", $player_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		if ($db->get_db()->affected_rows <= 0) {
			$return_codes[] = 1135;
			break;
		}


		$exp -= END_OF_ROUND;
		$days = ceil($exp / 86400);
		$exp = $days * 86400;
		$user_id = USER_ID;

		/*
		 * This is not meant to be cryptographically secure. The key is bound to the user and as long as it remains
		 * so it will be as secure as anything else.
		 */

		mt_srand(PAGE_START_TIME * PLAYER_ID);
		$key = 'REDACTED-TIME-' . sprintf('%03d', $days) . '-' . dechex($user_id) . '-' . dechex(END_OF_ROUND) . '-' . dechex(mt_rand());
		$type = 2;
		
		if (!($st = $db_user->get_db()->prepare('insert into gold_keys (`key`, `time`, `type`, `user`) values (?, ?, ?, ?)'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db_user->get_db()->errno . ") " . $db_user->get_db()->error);
			$return_codes[] = 1006;
			break;
		}
		
		$st->bind_param("siii", $key, $exp, $type, $user_id);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			break;
		}

		if ($db_user->get_db()->affected_rows <= 0) {
			$return_codes[] = 1126;
			break;
		}

		$return_codes[] = 1134;
	
	} while (false);


?>