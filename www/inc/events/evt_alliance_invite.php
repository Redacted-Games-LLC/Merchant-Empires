<?php
/**
 * Handles expiring alliance invitation requests.
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
	
	register_event(new Event_Alliance_Invite());

	class Event_Alliance_Invite extends Event {
		
		public function getRunTime() {
			return EVENT_ALLIANCE_RESPONSE_TIME;
		}

		public function run() {

			$this->incrementRun();
			
			global $db;
			$db = isset($db) ? $db : new DB;

			$time = time();

			$request_time = $time - (OPEN_REQUEST_DAYS * 3600 * 24);
			$reject_time = $time - (REJECTED_REQUEST_DAYS * 3600 * 24);

			if (!($st = $db->get_db()->prepare('delete from alliance_invitations where requested <= ? or (rejected > 0 and rejected <= ?)'))) {
				echo (__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				return;
			}
		
			$st->bind_param("ii", $request_time, $reject_time);
				
			if (!$st->execute()) {
				echo ("Query execution failed: (" . $st->errno . ") " . $st->error);
				return;
			}
		}
	}