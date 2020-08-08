<?php
/**
 * Periodically drops a timestamp on the output for logkeeping.
 *
 * Do not remove this or merge it into the main event processor. I want at
 * least one event in the pipeline at all times and this also serves as a
 * convenient copy/paste file for new events.
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
	
	register_event(new Event_Timestamp());

	/**
	 * The only purpose of this event is to drop a timestamp in the log.
	 *
	 * This could be done by the event processor without having a separate
	 * event but this provides us another indicator that the events are 
	 * being processed at all; a heartbeat.
	 */
	class Event_Timestamp extends Event {
		
		public function getRunTime() {
			return EVENT_TIMESTAMP_TIME;
		}

		public function run() {
			echo "TIMESTAMP (RFC850): " . date(DATE_RFC850) . "\n";
		}
	};