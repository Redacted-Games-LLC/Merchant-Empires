<?php
/**
 * Handles expiring new articles on a schedule.
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
	
	register_event(new Event_Expire_News());

	class Event_Expire_News extends Event {
		
		public function getRunTime() {
			return EVENT_EXPIRE_NEWS_TIME;
		}

		public function run() {

			$this->incrementRun();
		
			global $db;
			$db = isset($db) ? $db : new DB;

			$time = time();
			
			if (!($st = $db->get_db()->prepare('delete from news where expiration <= ?'))) {
				echo (__FILE__ . '::' . __LINE__ . "Prepare failed: (" . $db->get_db()->errno . ") " . $db->get_db()->error);
				return;
			}
		
			$st->bind_param("i", $time);
				
			if (!$st->execute()) {
				echo ("Query execution failed: (" . $st->errno . ") " . $st->error);
				return;
			}
		}
	}