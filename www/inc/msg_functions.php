<?php
/**
 * Functions for message-sending pages.
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
	

	function send_message($message, $targets = array(), $ttl = 600, $type = 1) {

		global $spacegame;

		$targets_copy = $targets;

		foreach ($targets_copy as $index => $target) {
			if (isset($spacegame['ignore_list'][$target])) {
				unset($targets[$index]);
			}
		}

		if (count($targets) <= 0) {
			return true;
		}

		global $db;
		$db = isset($db) ? $db : new DB;

		$time = PAGE_START_TIME;
		$exp = $time + $ttl;
		$sender = PLAYER_ID;

		if (!($st = $db->get_db()->prepare('insert into messages (posted, expiration, message, sender, `type`) values (?, ?, ?, ?, ?)'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			return false;
		}
		
		$st->bind_param("iisii", $time, $exp, $message, $sender, $type);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			return false;
		}

		$message_id = $db->last_insert_id('messages');

		if (!($st = $db->get_db()->prepare('insert into message_targets (message, target) values (?, ?)'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			return false;
		}

		$st->bind_param("ii", $message_id, $target);

		foreach ($targets as $target) {

			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
				return false;
			}
		}

		return true;
	}



	do { // Dummy Loop
		$db = isset($db) ? $db : new DB;

		$spacegame['ignore_list'] = array();
		
		$rs = $db->get_db()->query("select * from message_ignore where player = '". PLAYER_ID ."' or `ignore` = '". PLAYER_ID ."'");

		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$spacegame['ignore_list'][$row['player']] = true;
			$spacegame['ignore_list'][$row['target']] = true;
		}

	} while (false);


?>