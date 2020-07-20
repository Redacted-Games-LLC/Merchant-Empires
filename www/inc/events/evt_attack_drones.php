<?php
/**
 * Causes drones to occasionally fire on local enemy drones
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
	
	register_event(new Event_Attack_Drones());

	class Event_Attack_Drones extends Event {
		
		public function getRunTime() {
			return EVENT_ATTACK_DRONES_TIME;
		}

		public function run() {

			$this->incrementRun();
		
			global $db;
			$db = isset($db) ? $db : new DB;

			// Load up all sectors that have drones that belong to different
			// players. 


			// Have drones "fire" on drones when they don't match the alliance




		}

	};



?>

