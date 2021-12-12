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
	include_once('inc/common.php');
	include_once('inc/game.php');

	do { // Dummy Loop

		$db = isset($db) ? $db : new DB;

		$spacegame['ignore_list'] = array();
		$spacegame['ignore_index'] = array();
		$spacegame['ignore_list_count'] = 0;
		
		$rs = null;

		if (defined('LOAD_IGNORE_CAPTIONS')) {
			$rs = $db->get_db()->query("select message_ignore.*, players.caption from message_ignore, players where player = '". PLAYER_ID ."' and message_ignore.ignore = players.record_id");
		}
		else {
			$rs = $db->get_db()->query("select * from message_ignore where player = '". PLAYER_ID ."'");
		}

		$rs->data_seek(0);
		while ($row = $rs->fetch_assoc()) {
			$spacegame['ignore_list'][$row['record_id']] = $row;
			$spacegame['ignore_index'][$row['ignore']] = $row['record_id'];
			$spacegame['ignore_list_count']++;
		}
	} while (false);

	function send_message($message, $targets = array(), $ttl = 600, $type = 1) {

		global $spacegame;

		if (count($targets) <= 0) {
			return true;
		}

		global $db;
		$db = isset($db) ? $db : new DB;

		$time = PAGE_START_TIME;
		$exp = $time + $ttl;
		$sender = PLAYER_ID;

		if (!($st = $db->get_db()->prepare('insert into messages (posted, expiration, message, sender, `type`, `id`) values (?, ?, ?, ?, ?, ?)'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			return false;
		}
		
		$st->bind_param("iisiii", $time, $exp, $message, $sender, $type, $targets[0]);
		
		if (!$st->execute()) {
			$return_codes[] = 1006;
			error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
			return false;
		}

		$message_id = $db->last_insert_id('messages');

		if (!($st = $db->get_db()->prepare('insert into message_targets (message, target, sender) values (?, ?, ?)'))) {
			error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
			$return_codes[] = 1006;
			return false;
		}

		$st->bind_param("iii", $message_id, $target, $sender);

		foreach ($targets as $target) {

			if (!$st->execute()) {
				$return_codes[] = 1006;
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
				return false;
			}
		}

		return true;
	}

	// Check if there are any unread messages, then set message waiting flag to zero if none
	function unsetMessageWaiting() {
		$db = isset($db) ? $db : new DB;
		$player_id = PLAYER_ID;

		$rs = $db->get_db()->query("select * from message_targets where target = '$player_id' and message_targets.read = 0 and hidden = 0");
		$count = $rs->num_rows;

		if ($count == 0) {
			if (!($st = $db->get_db()->prepare("update players set unread_messages = 0 where record_id = ?"))) {
				error_log(__FILE__ . '::' . __LINE__ . " Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				$return_codes[] = 1006;
			}

			$st->bind_param("i", $player_id);

			if (!$st->execute()) {
				error_log(__FILE__ . '::' . __LINE__ . " Query execution failed: (" . $st->errno . ") " . $st->error);
				$return_codes[] = 1006;
			}

			if ($db->get_db()->affected_rows <= 0) {
				$return_codes[] = 1212;
			}
		}
	}