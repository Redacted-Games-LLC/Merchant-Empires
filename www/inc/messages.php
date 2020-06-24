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

		include_once('inc/pagination.php');

		if ($spacegame['page_number'] <= 0) {
			$spacegame['page_number'] = 1;
		}

		$spacegame['messages'] = array();
		$spacegame['message_count'] = 0;

		$spacegame['message_senders'] = array();
		$spacegame['message_sender_count'] = 0;

		$spacegame['message_receivers'] = array();
		$spacegame['message_receivers_count'] = 0;
			

		$db = isset($db) ? $db : new DB;

		if (defined('SENT_MSG_TYPE')) {
			// Sent folder

			$rs = $db->get_db()->query("select SQL_CALC_FOUND_ROWS messages.record_id as message_id, messages.posted, messages.message, messages.type, messages.id, message_targets.record_id from messages, message_targets where messages.record_id = message_targets.message and messages.sender = '". PLAYER_ID ."' and messages.`type` = '". SENT_MSG_TYPE ."' order by messages.posted desc limit ". (($spacegame['page_number'] - 1) * $spacegame['per_page']) . "," . $spacegame['per_page']);	
		}
		else {
			// Inbox

			if (defined('HIDDEN_MESSAGES')) {
				$rs = $db->get_db()->query("select SQL_CALC_FOUND_ROWS messages.record_id as message_id, messages.posted, messages.message, messages.type, messages.id, messages.sender, message_targets.record_id, message_targets.`read`, message_targets.hidden from messages, message_targets where messages.record_id = message_targets.message and message_targets.target = '". PLAYER_ID ."' order by messages.posted desc limit ". (($spacegame['page_number'] - 1) * $spacegame['per_page']) . "," . $spacegame['per_page']);
			}
			else {
				$rs = $db->get_db()->query("select SQL_CALC_FOUND_ROWS messages.record_id as message_id, messages.posted, messages.message, messages.type, messages.id, messages.sender, message_targets.record_id, message_targets.`read`, message_targets.hidden from messages, message_targets where message_targets.hidden <= 0 and messages.record_id = message_targets.message and message_targets.target = '". PLAYER_ID ."' order by messages.posted desc limit ". (($spacegame['page_number'] - 1) * $spacegame['per_page']) . "," . $spacegame['per_page']);
			}
		}

		$total_count = $db->found_rows();

		$rs->data_seek(0);

		while ($row = $rs->fetch_assoc()) {

			$spacegame['messages'][$row['record_id']] = $row;
			$spacegame['message_count']++;

			if (defined('SENT_MSG_TYPE')) {
				if ($row['id'] > 0) {
					$spacegame['message_receivers'][$row['id']] = null;
					$spacegame['message_receivers_count']++;
				}
			}
			else {
				if ($row['sender'] > 0) {
					$spacegame['message_senders'][$row['sender']] = null;
					$spacegame['message_sender_count']++;
				}
			}
			
		}

		
		if (defined('SENT_MSG_TYPE') && $spacegame['message_receivers_count'] > 0) {

			switch (SENT_MSG_TYPE) {
				case 1: // Player message
					$receivers = array_keys($spacegame['message_receivers']);

					$rs = $db->get_db()->query("select record_id, caption from players where record_id in (" . implode(',', $receivers) . ")");

					$rs->data_seek(0);

					while ($row = $rs->fetch_assoc()) {
						$spacegame['message_receivers'][$row['record_id']] = $row['caption'];
					}	

					break;


				case 2: // Alliance message
					$receivers = array_keys($spacegame['message_receivers']);

					$rs = $db->get_db()->query("select record_id, caption from alliances where record_id in (" . implode(',', $receivers) . ")");

					$rs->data_seek(0);

					while ($row = $rs->fetch_assoc()) {
						$spacegame['message_receivers'][$row['record_id']] = $row['caption'];
					}	

					break;
			}

		}
		else if ($spacegame['message_sender_count'] > 0) {

			$senders = array_keys($spacegame['message_senders']);

			$rs = $db->get_db()->query("select record_id, caption from players where record_id in (" . implode(',', $senders) . ")");

			$rs->data_seek(0);

			while ($row = $rs->fetch_assoc()) {
				$spacegame['message_senders'][$row['record_id']] = $row['caption'];
			}
		}


		$spacegame['max_pages'] = ceil($total_count / $spacegame['per_page']);

		if ($spacegame['page_number'] > $spacegame['max_pages']) {
			$spacegame['page_number'] = $spacegame['max_pages'];
		}

	} while (false);

?>