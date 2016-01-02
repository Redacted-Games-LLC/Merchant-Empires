<?php
/**
 * Loads messages
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

	do { // Dummy loop
		
		$spacegame['messages'] = array();
		$spacegame['message_count'] = 0;

		$spacegame['message_senders'] = array();
		$spacegame['message_sender_count'] = 0;

		$db = isset($db) ? $db : new DB;

		$rs = $db->get_db()->query("select messages.posted, messages.message, messages.type, messages.sender, message_targets.record_id, message_targets.`read` from messages, message_targets where messages.record_id = message_targets.message and message_targets.target = '". PLAYER_ID ."' order by messages.posted desc");

		$rs->data_seek(0);

		while ($row = $rs->fetch_assoc()) {
			$spacegame['messages'][$row['record_id']] = $row;
			$spacegame['message_count']++;

			if ($row['sender'] > 0) {
				$spacegame['message_senders'][$row['sender']] = null;
				$spacegame['message_sender_count']++;
			}
		}

		
		if ($spacegame['message_sender_count'] > 0) {
			$senders = array_keys($spacegame['message_senders']);

			$rs = $db->get_db()->query("select record_id, caption from players where record_id in (" . implode($senders) . ")");

			$rs->data_seek(0);
			while ($row = $rs->fetch_assoc()) {
				$spacegame['message_senders'][$row['record_id']] = $row['caption'];
			}
		}


	} while (false);


?>