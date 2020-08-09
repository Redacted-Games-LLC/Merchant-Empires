<?php
/**
 * Handles notifying players of a new message.
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

	include_once('inc/events.php');
	
	register_event(new Event_Messaging());

	class Event_Messaging extends Event {
		
		public function getRunTime() {
			return EVENT_MESSAGING_TIME;
		}

		public function run() {

			$this->incrementRun();
			
			global $db;
			$db = isset($db) ? $db : new DB;

			$time = time();

			// Delete expired messages first

			if (!($st = $db->get_db()->prepare('delete from messages where expiration <= ?'))) {
				echo (__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				return;
			}
		
			$st->bind_param("i", $time);
				
			if (!$st->execute()) {
				echo ("Query execution failed: (" . $st->errno . ") " . $st->error);
				return;
			}

			// Now find out if there are any unread messages for a player.

			$unread_targets = array();
			$unread_targets_count = 0;

			$rs = $db->get_db()->query("select distinct target from message_targets where `read` = 0");
			
			$rs->data_seek(0);
			while ($row = $rs->fetch_assoc()) {
				$unread_targets[] = $row['target'];
				$unread_targets_count += 1;
			}

			// Now update the player records to show they have an unread message.

			if (!($st = $db->get_db()->prepare('update players set unread_messages = 1 where record_id = ?'))) {
				echo (__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				return;
			}
		
			foreach ($unread_targets as $target_id) {
				
				$st->bind_param("i", $target_id);
				
				if (!$st->execute()) {
					echo ("Query execution failed: (" . $st->errno . ") " . $st->error);
					return;
				}
			}
		}
	};